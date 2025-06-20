<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\customers\contractModel;
use App\Models\customers\CustomerModel;
use Illuminate\Support\Facades\Storage;
use App\Models\globalsets\GlobalSetModel;

class CustomerEdit extends Component
{
    use WithFileUploads;

    public CustomerModel $customer;
    public $customer_id;
    public $customer_name, 
           $customer_taxid, 
           $customer_branch, 
           $customer_address_number, 
           $customer_address_district, 
           $customer_address_amphur, 
           $customer_branch_name, 
           $customer_address_province, 
           $customer_address_zipcode,
           $customer_contact_name_1,
           $customer_contact_phone_1,
           $customer_contact_email_1,
           $customer_contact_position_1,
           $customer_contact_name_2,
           $customer_contact_phone_2,
           $customer_contact_email_2,
           $customer_contact_position_2,
           $customer_thefirst_contact_name,
           $customer_thefirst_contact_phone,
           $customer_thefirst_acc_name,
           $customer_thefirst_acc_phone,
           $customer_thefirst_invoice_name,
           $customer_thefirst_invoice_phone,
           $customer_salary_cut_note,
           $customer_salary_note,
           $customer_clinic_name,
           $customer_employee_total_required,
           $customer_status,
           $customer_clinic_price;

    public $contracts = [];
    public $customer_cid_check = false;
    public $files = [];
    public $customer_files = [];
    public Collection $branchOptions;

    public function mount(CustomerModel $customer)
    {
        $this->customer_files = $customer->customer_files ?? [];
        $this->customer = $customer;
        $this->customer_id = $customer->id;

        // กำหนดค่าเริ่มต้น
        $this->customer_name = $customer->customer_name;
        $this->customer_taxid = $customer->customer_taxid;
        $this->customer_branch = $customer->customer_branch;
        $this->customer_branch_name = $customer->customer_branch_name;
        $this->customer_address_number = $customer->customer_address_number;
        $this->customer_address_district = $customer->customer_address_district;
        $this->customer_address_amphur = $customer->customer_address_amphur;
        $this->customer_address_province = $customer->customer_address_province;
        $this->customer_address_zipcode = $customer->customer_address_zipcode;
        $this->customer_contact_name_1 = $customer->customer_contact_name_1;
        $this->customer_contact_phone_1 = $customer->customer_contact_phone_1;
        $this->customer_contact_email_1 = $customer->customer_contact_email_1;
        $this->customer_contact_position_1 = $customer->customer_contact_position_1;
         $this->customer_contact_name_2 = $customer->customer_contact_name_2;
        $this->customer_contact_phone_2 = $customer->customer_contact_phone_2;
        $this->customer_contact_email_2 = $customer->customer_contact_email_2;
        $this->customer_contact_position_2 = $customer->customer_contact_position_2;
        $this->customer_thefirst_contact_name = $customer->customer_thefirst_contact_name; 
        $this->customer_thefirst_contact_phone = $customer->customer_thefirst_contact_phone; 
        $this->customer_thefirst_acc_name = $customer->customer_thefirst_acc_name; 
        $this->customer_thefirst_acc_phone = $customer->customer_thefirst_acc_name; 
        $this->customer_thefirst_invoice_name = $customer->customer_thefirst_invoice_name; 
        $this->customer_thefirst_invoice_phone = $customer->customer_thefirst_invoice_phone; 
        $this->customer_salary_cut_note = $customer->customer_salary_cut_note; 
        $this->customer_salary_note = $customer->customer_salary_note; 
        $this->customer_clinic_name = $customer->customer_clinic_name; 
        $this->customer_clinic_price = $customer->customer_clinic_price; 
        $this->customer_cid_check = $customer->customer_cid_check; 
        $this->customer_employee_total_required = $customer->customer_employee_total_required; 
        $this->customer_status = $customer->customer_status; 



        // ดึงข้อมูลสัญญา
        $this->contracts = $customer->contracts
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'round' => $c->contract_sort,
                    'number' => $c->contract_number,
                    'start_date' => $c->contract_start_date,
                    'end_date' => $c->contract_end_date,
                    'duration' => null,
                ];
            })
            ->toArray();

        foreach (array_keys($this->contracts) as $i) {
            $this->calculateDuration($i);
        }

        // ดึงรายการสาขา
        $set = GlobalSetModel::where('name', 'customerBranch')->with('values')->first();
        if ($set) {
            $this->branchOptions = $set->values->where('status', 'Enable')->values() ?? collect();
        }
    }

    public function update()
    {
        $this->validate([
            'customer_name' => 'required',
            'customer_taxid' => 'required',
            'files.*' => 'file',
        ]);

        $uploadedFilePaths = $this->customer->customer_files ?? [];

        foreach ($this->files as $file) {
            $filename = 'customer_' . $this->customer->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/customers', $filename, 'public');
            $uploadedFilePaths[] = $path;
        }

        // อัปเดตข้อมูลลูกค้า
        $this->customer->update([
            'customer_name' => $this->customer_name,
            'customer_taxid' => $this->customer_taxid,
            'customer_branch' => $this->customer_branch,
            'customer_branch_name' => $this->customer_branch_name,
            'customer_address_number' => $this->customer_address_number,
            'customer_address_district' => $this->customer_address_district,
            'customer_address_amphur' => $this->customer_address_amphur,
            'customer_address_province' => $this->customer_address_province,
            'customer_address_zipcode' => $this->customer_address_zipcode,
            'customer_contact_name_1' => $this->customer_contact_name_1,
            'customer_contact_phone_1' => $this->customer_contact_phone_1,
            'customer_contact_email_1' => $this->customer_contact_email_1,
            'customer_contact_position_1' => $this->customer_contact_position_1,
            'customer_contact_name_2' => $this->customer_contact_name_2,
            'customer_contact_phone_2' => $this->customer_contact_phone_2,
            'customer_contact_email_2' => $this->customer_contact_email_2,
            'customer_contact_position_2' => $this->customer_contact_position_2,
            'customer_thefirst_contact_name' => $this->customer_thefirst_contact_name, 
            'customer_thefirst_contact_phone'=> $this->customer_thefirst_contact_phone, 
            'customer_thefirst_acc_name' => $this->customer_thefirst_acc_name, 
            'customer_thefirst_acc_phone' => $this->customer_thefirst_acc_name, 
            'customer_thefirst_invoice_name' => $this->customer_thefirst_invoice_name, 
            'customer_thefirst_invoice_phone' => $this->customer_thefirst_invoice_phone, 
            'customer_salary_cut_note' => $this->customer_salary_cut_note, 
            'customer_salary_note' => $this->customer_salary_note, 
            'customer_clinic_name' => $this->customer_clinic_name, 
            'customer_clinic_price' => $this->customer_clinic_price, 
            'customer_cid_check' => $this->customer_cid_check, 
            'customer_employee_total_required' => $this->customer_employee_total_required, 
            'customer_status' => $this->customer_status, 


             
            'customer_files' => $uploadedFilePaths,
        ]);

        // ลบและเพิ่มสัญญาใหม่ทั้งหมด
        contractModel::where('customer_id', $this->customer->id)->delete();

        foreach ($this->contracts as $contract) {
            if (!empty($contract['round']) && !empty($contract['number'])) {
                $this->customer->contracts()->create([
                    'contract_sort' => $contract['round'],
                    'contract_number' => $contract['number'],
                    'contract_start_date' => $contract['start_date'],
                    'contract_end_date' => $contract['end_date'],
                ]);
            }
        }

        session()->flash('message', 'อัปเดตข้อมูลเรียบร้อย');
        return redirect()->route('customer.edit', $this->customer->id);
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

    public function removeFile($index)
    {
        $path = $this->customer_files[$index];

        // ลบจาก storage
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // ลบจาก array และอัปเดตลง DB
        unset($this->customer_files[$index]);
        $this->customer_files = array_values($this->customer_files);

        // ถ้ามี $customer object โหลดไว้
        if (isset($this->customer)) {
            $this->customer->update([
                'customer_files' => $this->customer_files,
            ]);
        }
    }

    public function calculateDuration($index)
    {
        if (!isset($this->contracts[$index]['start_date'])) {
            $this->contracts[$index]['duration'] = '';
            return;
        }

        try {
            $start = \Carbon\Carbon::parse(date(now()));
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

    public function render()
    {
        return view('livewire.customers.customer-edit')->layout('layouts.vertical-main', ['title' => 'แก้ไขลูกค้า']);
    }
}
