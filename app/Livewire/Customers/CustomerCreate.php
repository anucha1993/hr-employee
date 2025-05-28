<?php

namespace App\Livewire\Customers;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\customers\CustomerModel;
use App\Models\globalsets\GlobalSetModel;

class CustomerCreate extends Component
{
    public $customer_name = '',
        $customer_taxid = '',
        $customer_branch = '',
        $customer_branch_name = '';
    public $customer_address_number = '',
        $customer_address_district = '',
        $customer_address_amphur = '',
        $customer_address_province = '',
        $customer_address_zipcode = '';
    public $files = [];

    public Collection $branchOptions;
    public $contracts = [];
    use WithFileUploads;

    public function render()
    {
        return view('livewire.customers.customer-create')->layout('layouts.vertical-main', ['title' => 'customer']);
    }
    public function mount()
    {
        // โหลดรายการค่าของ GlobalSet ชื่อ customerBranch
        $set = GlobalSetModel::where('name', 'customerBranch')->with('values')->first();

        if ($set) {
            $this->branchOptions = $set->values->where('status', 'Enable')->values() ?? collect();
        }
    }

    public function store()
    {
        $this->validate([
            'customer_name' => 'required',
            'customer_taxid' => 'required|max:13|min:13',
            'files.*' => 'file|max:2048', // 2MB
        ]);

        // 1. Upload Files
        $uploadedFilePaths = [];
        foreach ($this->files as $file) {
            $filename = 'customer_' . $this->customer->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/customers', $filename, 'public');
            $uploadedFilePaths[] = $path;
        }

        // 2. Save Customer
        $customer = CustomerModel::create([
            'customer_name' => $this->customer_name,
            'customer_taxid' => $this->customer_taxid,
            'customer_branch_name' => $this->customer_branch_name,
            'customer_branch' => $this->customer_branch,
            'customer_address_number' => $this->customer_address_number,
            'customer_address_district' => $this->customer_address_district,
            'customer_address_amphur' => $this->customer_address_amphur,
            'customer_address_province' => $this->customer_address_province,
            'customer_address_zipcode' => $this->customer_address_zipcode,
            'customer_files' => $uploadedFilePaths,
            'created_by' => Auth::user()?->name ?? 'system',
        ]);

        // 3. Save Contracts
        foreach ($this->contracts as $contract) {
            if (!empty($contract['round']) && !empty($contract['number'])) {
                $customer->contracts()->create([
                    'contract_sort' => $contract['round'],
                    'contract_number' => $contract['number'],
                    'contract_start_date' => $contract['start_date'],
                    'contract_end_date' => $contract['end_date'],
                ]);
            }
        }

        session()->flash('message', 'บันทึกข้อมูลลูกค้าเรียบร้อยแล้ว');
        return redirect()->route('customer.index'); // เปลี่ยนเส้นทางตามที่คุณต้องการ
    }

    public function addContract()
    {
        $this->contracts[] = [
            'round' => '',
            'number' => '',
            'start_date' => '',
            'end_date' => '',
            'duration' => null,
        ];
    }

    public function removeContract($index)
    {
        unset($this->contracts[$index]);
        $this->contracts = array_values($this->contracts);
    }

    public function calculateDuration($index)
    {
        if (!isset($this->contracts[$index]['start_date']) || !isset($this->contracts[$index]['end_date'])) {
            $this->contracts[$index]['duration'] = '';
            return;
        }

        try {
            $start = \Carbon\Carbon::parse($this->contracts[$index]['start_date']);
            $end = \Carbon\Carbon::parse($this->contracts[$index]['end_date']);

            if ($end < $start) {
                $this->contracts[$index]['duration'] = '❌ วันที่สิ้นสุดน้อยกว่าวันเริ่ม';
                return;
            }

            $diff = $start->diff($end);

            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            $text = '';
            if ($years > 0) {
                $text .= "$years ปี ";
            }
            if ($months > 0) {
                $text .= "$months เดือน ";
            }
            if ($days > 0) {
                $text .= "$days วัน";
            }

            $this->contracts[$index]['duration'] = trim($text) ?: '0 วัน';
        } catch (\Exception $e) {
            $this->contracts[$index]['duration'] = '❌ วันที่ไม่ถูกต้อง';
        }
    }
}
