<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\customers\CustomerModel;
use App\Models\globalsets\GlobalSetModel;
use App\Models\globalsets\GlobalSetValueModel;
use App\Exports\CustomerEmployeeCountExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.vertical-main')]
#[Title('รายงานจำนวนพนักงานตามบริษัท')]
class CustomerEmployeeReport extends Component
{
    // Properties for filters
    public $customers = [];
    public $customer_status = 1;
    public $employee_status = null;
    public $date_from = null;
    public $date_to = null;
    public $selected_customer_ids = []; // เปลี่ยนเป็น array สำหรับหลายบริษัท
    public $previewData = [];
    public $showPreview = false;
    public $title = 'รายงานจำนวนพนักงานตามบริษัท';

    // For Livewire 3 naming
    protected static function resolveView()
    {
        return 'livewire.reports.customer-employee-report';
    }

    protected $queryString = [
        'selected_customer_ids' => ['except' => []],
        'employee_status' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    public function mount()
    {
        // Initialize date range to current month by default
        $this->date_from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->date_to = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        // Get all active customers
        $customers = CustomerModel::where('customer_status', $this->customer_status)
                    ->orderBy('customer_name')
                    ->get();
        
        // Get employee statuses from GlobalSetValue
        $employeeStatusSet = GlobalSetModel::where('name', 'EMP_STATUS')->first();
        $employeeStatuses = [];
        
        if ($employeeStatusSet) {
            $employeeStatuses = GlobalSetValueModel::where('global_set_id', $employeeStatusSet->id)
                                ->where('status', 'Enable')
                                ->get();
        }
        
        // Try both naming conventions for Livewire 3
        return view('livewire.reports.customer-employee-report', [
            'customerList' => $customers,
            'employeeStatuses' => $employeeStatuses,
            'title' => $this->title
        ]);
    }

    public function preview()
    {
        // วิธีการที่เรียบง่ายขึ้น เพื่อแก้ปัญหาการแสดงข้อมูล
        
        // Debug query
        DB::enableQueryLog();
        
        // ดึงข้อมูลลูกค้าตามเงื่อนไข แบบง่ายๆ
        $query = CustomerModel::query()
                ->with(['employees' => function($q) {
                    // กรองพนักงานตามสถานะ (ถ้ามีการระบุ)
                    if ($this->employee_status) {
                        $q->where('emp_status', $this->employee_status);
                    }
                    
                    // กรองตามวันที่เริ่มงาน (ถ้ามีการระบุ)
                    if ($this->date_from) {
                        $q->where('emp_start_date', '>=', Carbon::parse($this->date_from)->startOfDay());
                    }
                    
                    if ($this->date_to) {
                        $q->where('emp_start_date', '<=', Carbon::parse($this->date_to)->endOfDay());
                    }
                }]);
                
        // กรองตามสถานะของลูกค้า
        $query->where('customer_status', $this->customer_status);
        
        // กรองตามบริษัทที่เลือก
        if (!empty($this->selected_customer_ids)) {
            $query->whereIn('id', $this->selected_customer_ids);
        }
        
        // เรียงตามชื่อบริษัท
        $this->previewData = $query->orderBy('customer_name')->get();
        
        // Log queries for debugging
        $queries = DB::getQueryLog();
        session()->flash('last_query', $queries);
        
        // แปลงข้อมูลให้อยู่ในรูปแบบที่ต้องการ
        $data = collect($this->previewData);
        $this->previewData = $data->map(function($customer) {
            // นับพนักงานทั้งหมด
            $filteredEmployees = $customer->employees;
            
            // ตรวจสอบค่าสถานะที่มีอยู่จริงในข้อมูล พร้อมชื่อสถานะ
            $statusDebug = $filteredEmployees->map(function($employee) {
                $statusModel = GlobalSetValueModel::find($employee->emp_status);
                return $employee->emp_status . ' (' . ($statusModel ? $statusModel->value : 'ไม่พบ') . ')';
            })->unique()->toArray();
            
            // สร้างข้อมูลสำหรับแสดงผล
            return [
                'id' => $customer->id,
                'customer_name' => $customer->customer_name,
                'customer_status' => $customer->customer_status,
                'customer_taxid' => $customer->customer_taxid,
                // จำนวนพนักงานทั้งหมดที่ผ่านเงื่อนไขการกรอง
                'employee_count' => $filteredEmployees->count(),
                // พนักงานที่ทำงานอยู่ - เช็คค่าจาก helper
                'active_count' => $filteredEmployees->filter(function ($employee) {
                    return $this->isActiveEmployee($employee->emp_status);
                })->count(),
                // พนักงานที่ไม่ได้ทำงาน - เช็คค่าจาก helper
                'inactive_count' => $filteredEmployees->filter(function ($employee) {
                    return !$this->isActiveEmployee($employee->emp_status);
                })->count(),
                // เพิ่มข้อมูล debug สถานะที่พบในพนักงาน
                'debug_statuses' => $statusDebug,
            ];
        });
        
        $this->showPreview = true;
    }

    public function export()
    {
        // สร้าง preview data ก่อน (เพื่อให้มั่นใจว่าใช้ข้อมูลชุดเดียวกัน)
        $this->preview();
        
        if (count($this->previewData) == 0) {
            // ถ้าไม่มีข้อมูล ให้แจ้งเตือนผู้ใช้
            session()->flash('error', 'ไม่พบข้อมูลตามเงื่อนไขที่ระบุ');
            return null;
        }
        
        // Export filename
        $fileName = 'รายงานจำนวนพนักงานตามบริษัท_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
        
        // ส่งข้อมูลที่แสดงในหน้า preview ไปให้ Export class
        return Excel::download(new CustomerEmployeeCountExport($this->previewData), $fileName);
    }
    
    public function resetFilters()
    {
        $this->employee_status = null;
        $this->selected_customer_ids = [];
        $this->date_from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->date_to = Carbon::now()->format('Y-m-d');
        $this->showPreview = false;
        $this->previewData = [];
    }
    
    /**
     * ตรวจสอบว่าพนักงานมีสถานะเป็น active หรือไม่
     * 
     * @param int|null $status รหัสสถานะของพนักงาน (ID จาก GlobalSetValueModel)
     * @return bool
     */
    protected function isActiveEmployee($status)
    {
        // ค่า null หรือค่าว่างให้ถือว่าไม่ active
        if (is_null($status) || $status === '') {
            return false;
        }
        
        // ดึงข้อมูลสถานะจาก GlobalSetValueModel
        $statusValue = GlobalSetValueModel::find($status);
        
        if (!$statusValue) {
            return false;
        }
        
        // ตรวจสอบตามชื่อสถานะ
        $statusName = strtolower($statusValue->value);
        
        // พนักงานที่ active คือสถานะ "เริ่มงาน"
        return in_array($statusName, ['เริ่มงาน']);
    }
    
    /**
     * ตรวจสอบความสัมพันธ์ของข้อมูลในฐานข้อมูล
     */
    public function debugRelationship()
    {
        // ตรวจสอบว่ามีข้อมูลลูกค้าในฐานข้อมูลหรือไม่
        $customersCount = CustomerModel::count();
        
        // ตรวจสอบว่ามีข้อมูลพนักงานในฐานข้อมูลหรือไม่
        $employeesCount = DB::table('employee')->count();
        
        // ตรวจสอบว่ามีพนักงานที่เชื่อมโยงกับบริษัทหรือไม่
        $linkedEmployeesCount = DB::table('employee')
            ->whereNotNull('emp_factory_id')
            ->count();
        
        // ตรวจสอบสถานะพนักงาน
        $employeeStatusCounts = DB::table('employee')
            ->select('emp_status', DB::raw('count(*) as count'))
            ->groupBy('emp_status')
            ->get();
        
        // ตรวจสอบ 5 พนักงานแรกพร้อมรหัสบริษัท
        $sampleEmployees = DB::table('employee')
            ->select('id', 'emp_name', 'emp_factory_id', 'emp_status')
            ->limit(5)
            ->get();
            
        // ตรวจสอบ 5 บริษัทแรกและจำนวนพนักงาน
        $sampleCustomers = DB::table('customers as c')
            ->select('c.id', 'c.customer_name', DB::raw('count(e.id) as employee_count'))
            ->leftJoin('employee as e', 'c.id', '=', 'e.emp_factory_id')
            ->groupBy('c.id', 'c.customer_name')
            ->limit(5)
            ->get();
            
        // สรุปข้อมูล
        $debugInfo = [
            'customers_count' => $customersCount,
            'employees_count' => $employeesCount,
            'linked_employees_count' => $linkedEmployeesCount,
            'employee_status_counts' => $employeeStatusCounts,
            'sample_employees' => $sampleEmployees,
            'sample_customers' => $sampleCustomers,
        ];
        
        // แสดงผลใน session
        session()->flash('debug_info', $debugInfo);
        
        return $debugInfo;
    }
}