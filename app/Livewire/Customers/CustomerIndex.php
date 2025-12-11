<?php

namespace App\Livewire\Customers;

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\customers\CustomerModel;

class CustomerIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // เพื่อให้ใช้ bootstrap-5 ได้
    
    public $search = '';
    public $statusFilter = '';
    public $provinceFilter = '';

    protected $queryString = ['search', 'statusFilter', 'provinceFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingProvinceFilter()
    {
        $this->resetPage();
    }

    public function getCustomersProperty()
    {
        $query = CustomerModel::with('contracts');

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_taxid', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_address_province', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('customer_status', $this->statusFilter);
        }

        // Province filter
        if ($this->provinceFilter) {
            $query->where('customer_address_province', $this->provinceFilter);
        }

        return $query->latest()->paginate(10);
    }

    public function delete($id)
    {
        CustomerModel::findOrFail($id)->delete();
        session()->flash('message', 'ลบข้อมูลลูกค้าเรียบร้อยแล้ว');
    }

    public function render()
    {
        return view('livewire.customers.customer-index')
            ->layout('layouts.vertical-main', ['title' => 'รายชื่อลูกค้า']);
    }
}