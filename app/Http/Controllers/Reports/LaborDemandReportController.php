<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\customers\CustomerModel;
use App\Models\Employees\EmployeeModel;
use App\Models\globalsets\GlobalSetModel;
use App\Models\globalsets\GlobalSetValueModel;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Exports\LaborDemandExport;

class LaborDemandReportController extends Controller
{
    public function export(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $project_id = $request->input('project_id');
        $employee_status = $request->input('employee_status');
        
        // เตรียมข้อมูลรายงานเหมือนใน LaborDemandReport Livewire Component
        $reportData = $this->generateReportData($start_date, $end_date, $project_id, $employee_status);
        
        // สร้างชื่อไฟล์ Export
        $fileName = 'labor_demand_report_' . date('YmdHis') . '.xlsx';
        
        // ส่งข้อมูลไปยัง Export Class
        return Excel::download(new LaborDemandExport($reportData), $fileName);
    }
    
    private function generateReportData($start_date, $end_date, $project_id, $employee_status)
    {
        // เริ่มต้นด้วยการเลือกบริษัทตามเงื่อนไข
        $query = CustomerModel::query();
        
        // กรองตาม project_id ถ้ามีการเลือก
        if ($project_id) {
            $query->where('id', $project_id);
        }
        
        $customers = $query->get();
        $reportData = [];
        $totalDemand = 0;
        $totalStarted = 0;
        $totalResigned = 0;
        
        foreach ($customers as $customer) {
            // จำนวนความต้องการแรงงานจากค่า customer_employee_total_required
            $laborDemand = $customer->customer_employee_total_required ?? 0;
            
            // สร้าง query สำหรับพนักงานที่เริ่มงาน
            $startedQuery = EmployeeModel::where('emp_factory_id', $customer->id);
            
            // ตรวจสอบวันที่เริ่มงานให้อยู่ในช่วงที่กำหนด
            if ($start_date && $end_date) {
                $startedQuery->whereNotNull('emp_start_date')
                    ->whereBetween('emp_start_date', [$start_date, $end_date]);
            }
                
            // กรองตามสถานะพนักงาน (ถ้ามีการเลือก)
            if ($employee_status) {
                $startedQuery->where('emp_status', $employee_status);
            }
            
            // จำนวนพนักงานที่เริ่มงานในช่วงเวลาที่กำหนด
            $employeesStarted = $startedQuery->count();
            
            // สร้าง query สำหรับพนักงานที่ลาออก
            $resignedQuery = EmployeeModel::where('emp_factory_id', $customer->id);
            
            // ดึงค่า GlobalSet สำหรับสถานะลาออกและพ้นสภาพ
            $empStatusGlobalSet = GlobalSetModel::where('name', 'EMP_STATUS')->first();
            $resignStatusIds = [];
            
            if ($empStatusGlobalSet) {
                $resignStatusIds = GlobalSetValueModel::where('global_set_id', $empStatusGlobalSet->id)
                    ->where(function($q) {
                        $q->where('value', 'like', '%ลาออก%')
                          ->orWhere('value', 'like', '%พ้นสภาพ%')
                          ->orWhere('value', 'like', '%เลิกจ้าง%');
                    })
                    ->pluck('id')
                    ->toArray();
            }
            
            // ใช้เงื่อนไข OR ระหว่างสถานะลาออกกับวันที่ลาออก
            $resignedQuery->where(function($q) use ($resignStatusIds, $start_date, $end_date) {
                // เงื่อนไขที่ 1: มีสถานะเป็นลาออก/พ้นสภาพ
                if (!empty($resignStatusIds)) {
                    $q->whereIn('emp_status', $resignStatusIds);
                }
                
                // เงื่อนไขที่ 2: มีวันที่ลาออกอยู่ในช่วงที่กำหนด
                if ($start_date && $end_date) {
                    $q->orWhere(function($query) use ($start_date, $end_date) {
                        $query->whereNotNull('emp_resign_date')
                              ->whereBetween('emp_resign_date', [$start_date, $end_date]);
                    });
                }
            });
                
            // กรองตามสถานะพนักงานที่ผู้ใช้เลือก (ถ้ามีการเลือก)
            if ($employee_status) {
                $resignedQuery->where('emp_status', $employee_status);
            }
            
            // จำนวนพนักงานที่ลาออกในช่วงเวลาที่กำหนด
            $employeesResigned = $resignedQuery->count();
            
            // คำนวณเปอร์เซ็นต์ความสำเร็จ และ retention
            $successRate = $laborDemand > 0 ? ($employeesStarted / $laborDemand) * 100 : 0;
            $retentionRate = $employeesStarted > 0 ? (($employeesStarted - $employeesResigned) / $employeesStarted) * 100 : 0;
            
            // เพิ่มข้อมูลเข้าไปในอาร์เรย์ผลลัพธ์
            $reportData[] = [
                'customer_id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'labor_demand' => $laborDemand,
                'employees_started' => $employeesStarted,
                'employees_resigned' => $employeesResigned,
                'success_rate' => number_format($successRate, 2),
                'retention_rate' => number_format($retentionRate, 2),
            ];
            
            $totalDemand += $laborDemand;
            $totalStarted += $employeesStarted;
            $totalResigned += $employeesResigned;
        }
        
        // คำนวณรวมทั้งหมด
        $totalSuccessRate = $totalDemand > 0 ? ($totalStarted / $totalDemand) * 100 : 0;
        $totalRetentionRate = $totalStarted > 0 ? (($totalStarted - $totalResigned) / $totalStarted) * 100 : 0;
        
        // เพิ่มข้อมูลรวมทั้งหมด
        $reportData[] = [
            'customer_id' => 'total',
            'customer_name' => 'รวมทั้งหมด',
            'labor_demand' => $totalDemand,
            'employees_started' => $totalStarted,
            'employees_resigned' => $totalResigned,
            'success_rate' => number_format($totalSuccessRate, 2),
            'retention_rate' => number_format($totalRetentionRate, 2),
        ];
        
        return $reportData;
    }
}
