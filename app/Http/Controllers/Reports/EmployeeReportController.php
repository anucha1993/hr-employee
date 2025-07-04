<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\EmployeesReportExport;
use App\Models\Employees\EmployeeModel;
use App\Models\customers\CustomerModel;
use App\Models\globalsets\GlobalSetModel;
use App\Models\globalsets\GlobalSetValueModel;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class EmployeeReportController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลบริษัทลูกค้าทั้งหมด
        $customers = CustomerModel::where('customer_status', 1)
                    ->orderBy('customer_name')
                    ->get();
        
        // ดึงสถานะพนักงานจาก GlobalSetValue
        $employeeStatusSet = GlobalSetModel::where('name', 'EMP_STATUS')->first();
        $employeeStatuses = [];
        
        if ($employeeStatusSet) {
            $employeeStatuses = GlobalSetValueModel::where('global_set_id', $employeeStatusSet->id)
                                ->where('status', 'Enable')
                                ->get();
        }
        
        // สร้างข้อมูลสำหรับ dropdown เดือน
        $months = [
            1 => 'มกราคม',
            2 => 'กุมภาพันธ์',
            3 => 'มีนาคม',
            4 => 'เมษายน',
            5 => 'พฤษภาคม',
            6 => 'มิถุนายน',
            7 => 'กรกฎาคม',
            8 => 'สิงหาคม',
            9 => 'กันยายน',
            10 => 'ตุลาคม',
            11 => 'พฤศจิกายน',
            12 => 'ธันวาคม'
        ];
        
        // สร้างข้อมูลสำหรับ dropdown ปี
        $currentYear = date('Y');
        $years = range($currentYear - 5, $currentYear + 1);
        
        // ดึงข้อมูลพนักงานที่มีการสรรหา (อาจต้องปรับตามโครงสร้างข้อมูลจริง)
        $recruitedEmployees = EmployeeModel::whereNotNull('emp_recruiter_id')
                            ->select('id', 'emp_name', 'emp_code')
                            ->get();
        
        return view('reports.employee-report', compact('customers', 'employeeStatuses', 'months', 'years', 'recruitedEmployees'));
    }
    
    public function export(Request $request)
    {
        // ตรวจสอบและเตรียมข้อมูลสำหรับการกรอง
        $filters = [];
        
        // กรองตามบริษัทที่เลือก
        if ($request->has('customers') && !empty($request->customers)) {
            $filters['customer_ids'] = $request->customers;
        }
        
        // กรองตามเดือนเริ่มงาน
        if ($request->has('start_month') && !empty($request->start_month)) {
            $filters['start_month'] = $request->start_month;
        }
        
        // กรองตามปีเริ่มงาน
        if ($request->has('start_year') && !empty($request->start_year)) {
            $filters['start_year'] = $request->start_year;
        }
        
        // กรองตามช่วงวันที่เริ่มงาน
        if ($request->has('date_from') && !empty($request->date_from)) {
            $filters['date_from'] = Carbon::parse($request->date_from)->startOfDay();
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $filters['date_to'] = Carbon::parse($request->date_to)->endOfDay();
        }
        
        // กรองตามสถานะพนักงาน
        if ($request->has('employee_status') && !empty($request->employee_status)) {
            $filters['employee_status'] = $request->employee_status;
        }
        
        // กรองตามรายชื่อพนักงานที่ตรงกับรายชื่อสรรหา
        if ($request->has('recruited_employees') && !empty($request->recruited_employees)) {
            $filters['recruited_employees'] = $request->recruited_employees;
        }
        
        // ชื่อไฟล์ Excel ที่จะดาวน์โหลด
        $fileName = 'รายงานข้อมูลพนักงาน_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        
        // ส่งออก Excel
        return Excel::download(new EmployeesReportExport($filters), $fileName);
    }
}
