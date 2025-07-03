<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\customers\CustomerModel;
use App\Models\Employees\EmployeeModel;
use App\Models\globalsets\GlobalSetModel;
use App\Models\globalsets\GlobalSetValueModel;
use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class EmployeeIndex extends Component
{
    use WithPagination;

    // Define the layout for this component (Livewire v3 way)
    public function layout()
    {
        return 'layouts.vertical-main';
    }
    
    // Define title for the layout
    protected $title = 'พนักงาน';

    public $search = '';
    public $perPage = 10;
    
    // Filter properties
    public $filter_status = '';
    public $filter_factory = '';
    public $filter_age_from = '';
    public $filter_age_to = '';
    public $filter_department = '';
    public $isFilterOpen = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterFactory()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filter_status = '';
        $this->filter_factory = '';
        $this->filter_age_from = '';
        $this->filter_age_to = '';
        $this->filter_department = '';
        $this->resetPage();
        
        // Dispatch browser event to reset Select2 components
        // For Livewire 3, use dispatch for browser events
        $this->dispatch('reset-select2');
    }
    
    public function toggleFilter()
    {
        $this->isFilterOpen = !$this->isFilterOpen;
        
        // Dispatch browser event to notify filter toggle for Select2 initialization
        $this->dispatch('filterToggled');
    }

    public function delete($id)
    {
        EmployeeModel::find($id)?->delete();
        session()->flash('message', 'ลบข้อมูลพนักงานเรียบร้อยแล้ว');
    }
    
    public function exportExcel()
    {
        $filters = [
            'search' => $this->search,
            'status' => $this->filter_status,
            'factory' => $this->filter_factory,
            'age_from' => $this->filter_age_from,
            'age_to' => $this->filter_age_to,
            'department' => $this->filter_department,
        ];
        
        $timestamp = Carbon::now()->format('Ymd_His');
        return Excel::download(new EmployeesExport($filters), "employees_export_{$timestamp}.xlsx");
    }

    public function render()
    {
        $query = EmployeeModel::query();
        
        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('emp_name', 'like', "%{$this->search}%")
                  ->orWhere('emp_code', 'like', "%{$this->search}%")
                  ->orWhere('emp_phone', 'like', "%{$this->search}%");
            });
        }
        
        // Apply status filter
        if (!empty($this->filter_status)) {
            $query->where('emp_status', $this->filter_status);
        }
        
        // Apply factory filter
        if (!empty($this->filter_factory)) {
            $query->where('emp_factory_id', $this->filter_factory);
        }
        
        // Apply age range filter
        if (!empty($this->filter_age_from) && is_numeric($this->filter_age_from)) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, emp_birthdate, CURDATE()) >= ?', [$this->filter_age_from]);
        }
        
        if (!empty($this->filter_age_to) && is_numeric($this->filter_age_to)) {
            $query->whereRaw('TIMESTAMPDIFF(YEAR, emp_birthdate, CURDATE()) <= ?', [$this->filter_age_to]);
        }
        
        // Apply department filter
        if (!empty($this->filter_department)) {
            $query->where('emp_department', 'like', "%{$this->filter_department}%");
        }

        $employees = $query->latest()->paginate($this->perPage);

        // Get all employees for statistics (without pagination)
        $allEmployeesForStats = EmployeeModel::all();

        $factories = CustomerModel::all();
        $empStatusGlobalSet = GlobalSetModel::where('name', 'EMP_STATUS')->first();
        $statusOptions = $empStatusGlobalSet ? $empStatusGlobalSet->values : collect([]);
        
        // Get unique departments for filter dropdown
        $departments = EmployeeModel::select('emp_department')
            ->whereNotNull('emp_department')
            ->where('emp_department', '<>', '')
            ->distinct()
            ->pluck('emp_department');

        return view('livewire.employees.employee-index', [
            'employees' => $employees,
            'allEmployeesForStats' => $allEmployeesForStats,
            'factories' => $factories,
            'statusOptions' => $statusOptions,
            'departments' => $departments,
            'title' => 'พนักงาน',
        ])->layout('layouts.vertical-main', ['title' => 'พนักงาน']);
    }
}
