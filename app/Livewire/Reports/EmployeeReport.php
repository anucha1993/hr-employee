<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\customers\CustomerModel;
use App\Models\Employees\EmployeeModel;
use App\Models\globalsets\GlobalSetModel;
use App\Models\globalsets\GlobalSetValueModel;
use Carbon\Carbon;
use App\Exports\EmployeesReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.vertical-main')]
#[Title('รายงานข้อมูลพนักงาน')]
class EmployeeReport extends Component
{
    use WithPagination;
    
    // กำหนดตัวแปรสำหรับฟอร์มกรอง
    public $customer_ids = [];
    public $start_month;
    public $start_year;
    public $date_from;
    public $date_to;
    public $employee_status = [];
    public $recruited_employees = []; // เปลี่ยนเป็น recruiter IDs

    public function mount()
    {
        // อาจตั้งค่าเริ่มต้นตรงนี้ถ้าต้องการ
        $this->start_year = date('Y');
    }

    public function resetFilters()
    {
        $this->customer_ids = [];
        $this->start_month = null;
        $this->start_year = date('Y');
        $this->date_from = null;
        $this->date_to = null;
        $this->employee_status = [];
        $this->recruited_employees = [];
        $this->resetPage();
    }

    public function updatedCustomerIds()
    {
        $this->resetPage();
    }

    public function updatedStartMonth()
    {
        $this->resetPage();
    }

    public function updatedStartYear()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedEmployeeStatus()
    {
        $this->resetPage();
    }

    public function updatedRecruitedEmployees()
    {
        $this->resetPage();
    }

    public function exportExcel()
    {
        $filters = [
            'customer_ids' => $this->customer_ids,
            'start_month' => $this->start_month,
            'start_year' => $this->start_year,
            'date_from' => $this->date_from ? Carbon::parse($this->date_from)->startOfDay() : null,
            'date_to' => $this->date_to ? Carbon::parse($this->date_to)->endOfDay() : null,
            'employee_status' => $this->employee_status,
            'recruited_employees' => $this->recruited_employees,
        ];

        // ชื่อไฟล์ Excel ที่จะดาวน์โหลด
        $fileName = 'รายงานข้อมูลพนักงาน_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        
        // ส่งออก Excel
        return Excel::download(new EmployeesReportExport($filters), $fileName);
    }
    
    public function preview()
    {
        // ดึงข้อมูลพนักงานตามเงื่อนไข filter เดียวกับการ export
        $query = EmployeeModel::query()->with(['factory', 'status', 'recruiter']);
        
        // กรองตามบริษัทที่เลือก
        if (!empty($this->customer_ids)) {
            $query->whereIn('emp_factory_id', $this->customer_ids);
        }
        
        // กรองตามเดือนเริ่มงาน
        if (!empty($this->start_month)) {
            $query->whereMonth('emp_start_date', $this->start_month);
        }
        
        // กรองตามปีเริ่มงาน
        if (!empty($this->start_year)) {
            $query->whereYear('emp_start_date', $this->start_year);
        }
        
        // กรองตามช่วงวันที่เริ่มงาน
        if (!empty($this->date_from)) {
            $query->where('emp_start_date', '>=', Carbon::parse($this->date_from)->startOfDay());
        }
        
        if (!empty($this->date_to)) {
            $query->where('emp_start_date', '<=', Carbon::parse($this->date_to)->endOfDay());
        }
        
        // กรองตามสถานะพนักงาน
        if (!empty($this->employee_status)) {
            $query->whereIn('emp_status', $this->employee_status);
        }
        
        // กรองตามรายชื่อสรรหา
        if (!empty($this->recruited_employees)) {
            $query->whereIn('emp_recruiter_id', $this->recruited_employees);
        }
        
        return $query->orderBy('emp_code')->paginate(10);
    }
    
    public function render()
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
        
        // ดึงข้อมูลรายชื่อสรรหา
        $recruitedEmployees = GlobalSetValueModel::where('global_set_id', GlobalSetModel::where('name', 'RECRUITER')->first()->id ?? 0)->get();
        
        // ดึงข้อมูลตัวอย่างสำหรับ preview
        $employees = $this->preview();

        return view('livewire.reports.employee-report', [
            'customers' => $customers,
            'employeeStatuses' => $employeeStatuses,
            'months' => $months,
            'years' => $years,
            'recruitedEmployees' => $recruitedEmployees,
            'employees' => $employees
        ]);
    }
}