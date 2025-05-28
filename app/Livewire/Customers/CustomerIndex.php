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

    public function getCustomersProperty()
    {
        return CustomerModel::with('contracts')->latest()->paginate(10);
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