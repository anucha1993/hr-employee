<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\CustomerEmployeeCountExport;
use App\Models\customers\CustomerModel;
use App\Models\globalsets\GlobalSetModel;
use App\Models\globalsets\GlobalSetValueModel;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class CustomerEmployeeReportController extends Controller
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
        
        return view('reports.customer-employee', compact('customers', 'employeeStatuses'))->layout('layouts.vertical-main', ['title' => 'รายงานจำนวนพนักงานตามบริษัท']);
    }
    
    public function export(Request $request)
    {
        // ตรวจสอบและเตรียมข้อมูลสำหรับการกรอง
        $filters = [];
        
        // กรองตามสถานะลูกค้า
        if ($request->has('customer_status')) {
            $filters['customer_status'] = $request->customer_status;
        }
        
        // กรองตามบริษัทที่เลือก
        if ($request->has('customers') && !empty($request->customers)) {
            $filters['customer_ids'] = $request->customers;
        }
        
        // กรองตามสถานะพนักงาน
        if ($request->has('employee_status')) {
            $filters['employee_status'] = $request->employee_status;
        }
        
        // กรองตามช่วงเวลาเริ่มงาน
        if ($request->has('date_from') && !empty($request->date_from)) {
            $filters['date_from'] = Carbon::parse($request->date_from)->startOfDay();
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $filters['date_to'] = Carbon::parse($request->date_to)->endOfDay();
        }
        
        // ชื่อไฟล์ Excel ที่จะดาวน์โหลด
        $fileName = 'รายงานจำนวนพนักงานตามบริษัท_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        
        // ส่งออก Excel
        return Excel::download(new CustomerEmployeeCountExport($filters), $fileName);
    }
}
