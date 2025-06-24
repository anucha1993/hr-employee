<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\customers\CustomerModel;
use App\Models\Employees\EmployeeModel;
use App\Models\globalsets\GlobalSetModel;

class EmployeeIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        EmployeeModel::find($id)?->delete();
        session()->flash('message', 'ลบข้อมูลลูกค้าเรียบร้อยแล้ว');
    }

    public function render()
    {
        $employees = EmployeeModel::query()
            ->when($this->search, function ($query) {
                $query
                    ->where('emp_name', 'like', "%{$this->search}%")
                    ->orWhere('emp_code', 'like', "%{$this->search}%")
                    ->orWhere('emp_phone', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate($this->perPage);

        $factories = CustomerModel::all();
        $statusOptions = GlobalSetModel::find(5)?->values;

        return view('livewire.employees.employee-index', [
            'employees' => $employees,
            'factories' => $factories,
            'statusOptions' => $statusOptions,
        ])->layout('layouts.vertical-main', ['title' => 'พนักงาน']);
    }
}
