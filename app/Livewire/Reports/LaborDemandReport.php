<?php

namespace App\Livewire\Reports;

use App\Models\customers\CustomerModel;
use App\Models\Employees\EmployeeModel;
use App\Models\globalsets\GlobalSetModel;
use App\Models\globalsets\GlobalSetValueModel;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Collection;

#[Layout('layouts.vertical-main')]
#[Title('สถิติจำนวนความต้องการแรงงาน')]
class LaborDemandReport extends Component
{
    use WithPagination;
    
    public $title = 'สถิติจำนวนความต้องการแรงงาน';
    public $start_date;
    public $end_date;
    public $project_id;
    public $employee_status = null; // เพิ่มตัวกรองสถานะพนักงาน
    public $showPreview = false;
    public $reportData = [];
    public $chartData = [];
    public $showType = 'table'; // table, pie, bar
    
    public function mount()
    {
        // กำหนดค่าเริ่มต้น - ช่วงเวลา 1 เดือนล่าสุด
        $this->end_date = Carbon::now()->format('Y-m-d');
        $this->start_date = Carbon::now()->subMonth()->format('Y-m-d');
    }
    
    public function resetFilters()
    {
        $this->reset(['start_date', 'end_date', 'project_id', 'employee_status', 'showPreview', 'reportData', 'chartData']);
        $this->mount();
        
        // ส่งคำสั่งให้รีเซ็ต Select2
        $this->dispatch('reset-select2');
    }
    
    public function preview()
    {
        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.required' => 'กรุณาระบุวันที่เริ่มต้น',
            'end_date.required' => 'กรุณาระบุวันที่สิ้นสุด',
            'end_date.after_or_equal' => 'วันที่สิ้นสุดต้องเป็นวันที่หลังจากวันที่เริ่มต้น'
        ]);
        
        $this->generateReport();
        $this->showPreview = true;
    }
    
    private function generateReport()
    {
        // เริ่มต้นด้วยการเลือกบริษัทตามเงื่อนไข
        $query = CustomerModel::query();
        
        // กรองตาม project_id ถ้ามีการเลือก
        if ($this->project_id) {
            $query->where('id', $this->project_id);
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
            if ($this->start_date && $this->end_date) {
                $startedQuery->whereNotNull('emp_start_date')
                    ->whereBetween('emp_start_date', [$this->start_date, $this->end_date]);
            }
                
            // กรองตามสถานะพนักงาน (ถ้ามีการเลือก)
            if ($this->employee_status) {
                $startedQuery->where('emp_status', $this->employee_status);
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
            $resignedQuery->where(function($q) use ($resignStatusIds) {
                // เงื่อนไขที่ 1: มีสถานะเป็นลาออก/พ้นสภาพ
                if (!empty($resignStatusIds)) {
                    $q->whereIn('emp_status', $resignStatusIds);
                }
                
                // เงื่อนไขที่ 2: มีวันที่ลาออกอยู่ในช่วงที่กำหนด
                if ($this->start_date && $this->end_date) {
                    $q->orWhere(function($query) {
                        $query->whereNotNull('emp_resign_date')
                              ->whereBetween('emp_resign_date', [$this->start_date, $this->end_date]);
                    });
                }
            });
                
            // กรองตามสถานะพนักงานที่ผู้ใช้เลือก (ถ้ามีการเลือก)
            if ($this->employee_status) {
                $resignedQuery->where('emp_status', $this->employee_status);
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
        
        $this->reportData = collect($reportData);
        
        // เตรียมข้อมูลสำหรับกราฟ
        $this->prepareChartData();
        
        // ส่งสัญญาณให้อัพเดทกราฟ
        if ($this->showType === 'bar' || $this->showType === 'pie') {
            $this->dispatch('updateCharts');
        }
    }
    
    private function prepareChartData()
    {
        // ใช้ Collection เพื่อจัดการข้อมูลสำหรับกราฟ
        $filteredData = collect($this->reportData)->where('customer_id', '!=', 'total');
        
        $this->chartData = [
            'labels' => $filteredData->pluck('customer_name')->toArray(),
            'demandData' => $filteredData->pluck('labor_demand')->toArray(),
            'startedData' => $filteredData->pluck('employees_started')->toArray(),
            'resignedData' => $filteredData->pluck('employees_resigned')->toArray(),
            'successRateData' => $filteredData->pluck('success_rate')->toArray(),
            'retentionRateData' => $filteredData->pluck('retention_rate')->toArray(),
        ];
    }
    
    public function setShowType($type)
    {
        $this->showType = $type;
        
        // ส่งสัญญาณให้อัพเดทกราฟ
        if ($type === 'bar' || $type === 'pie') {
            $this->dispatch('updateCharts');
        }
    }
    
    public function updatedChartData()
    {
        $this->dispatch('chartDataUpdated');
    }
    
    public function exportExcel()
    {
        // ถ้ายังไม่มีข้อมูล ให้สร้างรายงานก่อน
        if (empty($this->reportData) || count($this->reportData) === 0) {
            $this->generateReport();
        }
        
        // ส่งข้อมูลไปยัง Export Controller
        return redirect()->route('labor-demand.export', [
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'project_id' => $this->project_id,
            'employee_status' => $this->employee_status,
        ]);
    }
    
    public function render()
    {
        $projects = CustomerModel::orderBy('customer_name')->get();
        
        // ดึงสถานะพนักงานจาก GlobalSetValue
        $empStatusGlobalSet = GlobalSetModel::where('name', 'EMP_STATUS')->first();
        $employeeStatuses = $empStatusGlobalSet ? GlobalSetValueModel::where('global_set_id', $empStatusGlobalSet->id)
                           ->where('status', 'Enable')
                           ->get() : collect([]);
        
        return view('livewire.reports.labor-demand-report', [
            'projects' => $projects,
            'employeeStatuses' => $employeeStatuses,
        ]);
    }
}
