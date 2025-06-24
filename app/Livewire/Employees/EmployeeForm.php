<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;
use App\Models\customers\CustomerModel;
use App\Models\employees\EmployeeModel;
use Illuminate\Support\Facades\Storage;
use App\Models\globalsets\GlobalSetModel;
use App\Models\globalsets\GlobalSetValueModel;

class EmployeeForm extends Component
{
    use WithFileUploads;

    public $emp_id;
    public $emp_name;
    public $emp_phone;
    public $emp_recruiter_id;
    public $emp_code;
    public $emp_department;
    public $emp_gender;
    public $emp_birthdate;
    public $emp_idcard;
    public $emp_education;
    public $emp_factory_id;
    public $emp_address_current;
    public $emp_address_register;
    public $emp_start_date;
    public $emp_medical_right;
    public $emp_contract_type = 'สัญญาระยะยาว';
    public $emp_contract_start;
    public $emp_contract_end;
    public $emp_resign_date;
    public $emp_resign_reason;
    public $emp_status;
    public $emp_emergency_contacts = [['name' => '', 'phone' => '', 'relation' => ''], ['name' => '', 'phone' => '', 'relation' => '']];
    public $emp_files = [];

    public function mount($id = null)
    {
        if ($id) {
            $this->emp_id = $id;
            $employee = EmployeeModel::findOrFail($id);
            $this->fill($employee->toArray());
            // แปลงวันให้เป็น Y-m-d string
            $this->emp_birthdate = optional($employee->emp_birthdate)->format('Y-m-d');
            $this->emp_start_date = optional($employee->emp_start_date)->format('Y-m-d');
            $this->emp_contract_start = optional($employee->emp_contract_start)->format('Y-m-d');
            $this->emp_contract_end = optional($employee->emp_contract_end)->format('Y-m-d');
            $this->emp_resign_date = optional($employee->emp_resign_date)->format('Y-m-d');

            if (is_null($this->emp_emergency_contacts)) {
                $this->emp_emergency_contacts = [['name' => '', 'phone' => '', 'relation' => ''], ['name' => '', 'phone' => '', 'relation' => '']];
            }
        } else {
            $this->emp_emergency_contacts = [['name' => '', 'phone' => '', 'relation' => ''], ['name' => '', 'phone' => '', 'relation' => '']];
        }
    }

    public function getEmpAgeProperty()
    {
        return $this->emp_birthdate ? now()->diffInYears(Carbon::parse($this->emp_birthdate)) : null;
    }

    public function getEmpWorkdaysProperty()
    {
        if (!$this->emp_start_date) {
            return [0, 0, 0];
        }
        $start = Carbon::parse($this->emp_start_date);
        $diff = now()->diff($start);
        return [$diff->y, $diff->m, $diff->d];
    }

    public function updatedEmpStatus($value)
    {
        if ($value === 'เริ่มงาน') {
            $this->validate([
                'emp_code' => 'required',
                'emp_department' => 'required',
                'emp_address_current' => 'required',
                'emp_address_register' => 'required',
                'emp_start_date' => 'required|date',
                'emp_medical_right' => 'required',
            ]);
        } elseif ($value === 'ลาออก') {
            $this->validate([
                'emp_resign_reason' => 'required',
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'emp_name' => 'required|string',
            'emp_idcard' => 'required|digits:13',
            'emp_status' => 'required',
            'emp_contract_type' => 'required',
            'emp_birthdate' => 'required',
            'emp_phone' => 'required',
            'emp_emergency_contacts' => 'array',
        ]);

        $data = [
            'emp_name' => $this->emp_name,
            'emp_phone' => $this->emp_phone,
            'emp_recruiter_id' => $this->emp_recruiter_id,
            'emp_code' => $this->emp_code,
            'emp_department' => $this->emp_department,
            'emp_gender' => $this->emp_gender,
            'emp_birthdate' => $this->emp_birthdate,
            'emp_idcard' => $this->emp_idcard,
            'emp_education' => $this->emp_education,
            'emp_factory_id' => $this->emp_factory_id,
            'emp_address_current' => $this->emp_address_current,
            'emp_address_register' => $this->emp_address_register,
            'emp_start_date' => $this->emp_start_date,
            'emp_medical_right' => $this->emp_medical_right,
            'emp_contract_type' => $this->emp_contract_type,
            'emp_contract_start' => $this->emp_contract_start,
            'emp_contract_end' => $this->emp_contract_end,
            'emp_resign_date' => $this->emp_resign_date,
            'emp_resign_reason' => $this->emp_resign_reason,
            'emp_status' => $this->emp_status,
            'emp_emergency_contacts' => $this->emp_emergency_contacts,
        ];

        // อัปโหลดไฟล์
        $uploadedPaths = [];

        if ($this->emp_files) {
            if (!$this->emp_id) {
                $employee = EmployeeModel::create($data);
                $this->emp_id = $employee->id;
            }

            $folder = 'uploads/employees/' . $this->emp_id;

            foreach ($this->emp_files as $file) {
                $originalName = $file->getClientOriginalName(); // ใช้ชื่อเดิม
                $path = $file->storeAs($folder, $originalName, 'public');
                $uploadedPaths[] = $path;
            }

            $existing = EmployeeModel::find($this->emp_id)?->emp_files ?? [];
            $data['emp_files'] = array_merge($existing, $uploadedPaths);
        }

        if ($this->emp_id) {
            $employee = EmployeeModel::find($this->emp_id);
            if ($employee) {
                $employee->update($data);
            }
        } else {
            $employee = EmployeeModel::create($data);
            $this->emp_id = $employee->id;
        }

        session()->flash('success', 'บันทึกข้อมูลพนักงานเรียบร้อย');
        return redirect()->route('employees.index');
    }

   public function removeFile($index)
{
    $employee = EmployeeModel::find($this->emp_id);
    if (!$employee) {
        return;
    }

    $files = $employee->emp_files ?? [];

    if (isset($files[$index])) {
        $filePath = $files[$index];

        // ลบจาก disk
        Storage::disk('public')->delete($filePath);

        // ลบจาก array และบันทึกกลับ
        unset($files[$index]);
        $files = array_values($files); // reset index
        $employee->update([
            'emp_files' => $files,
        ]);

        // 👉 refresh เพื่อให้ Livewire อัปเดต
        $this->emp_files = $files;
    }
}


    public function render()
    {
        return view('livewire.employees.employee-form', [
            'recruiters' => \App\Models\User::all(),
            'educationOptions' => GlobalSetModel::find(3)?->values,
            'medicalOptions' => GlobalSetModel::find(4)?->values,
            'statusOptions' => GlobalSetModel::find(5)?->values,
            'recruiterOptions' => GlobalSetModel::find(6)?->values,
            'factories' => CustomerModel::all(),
        ])->layout('layouts.vertical-main', ['title' => 'customer']);
    }
}
