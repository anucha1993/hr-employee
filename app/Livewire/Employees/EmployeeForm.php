<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;
use App\Models\customers\CustomerModel;
use App\Models\Employees\EmployeeModel;
use App\Models\Geo\Amphure;
use App\Models\Geo\District;
use App\Models\Geo\Province;
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
    
    // Old address fields (will be kept for compatibility)
    public $emp_address_current;
    public $emp_address_register;
    
    // New address fields for current address
    public $current_province_id;
    public $current_province_code;
    public $current_amphur_id;
    public $current_amphur_code;
    public $current_district_id;
    public $current_district_code;
    public $current_zipcode;
    public $current_address_details;
    
    // New address fields for registered address
    public $registered_province_id;
    public $registered_province_code;
    public $registered_amphur_id;
    public $registered_amphur_code;
    public $registered_district_id;
    public $registered_district_code;
    public $registered_zipcode;
    public $registered_address_details;
    
    // Address dropdown options
    public $provinces = [];
    public $currentAmphures = [];
    public $currentDistricts = [];
    public $registeredAmphures = [];
    public $registeredDistricts = [];
    
    public $emp_start_date;
    public $emp_medical_right;
    public $factory_search;
    public $medical_search;
    public $emp_contract_type = 'สัญญาระยะยาว';
    public $emp_contract_start;
    public $emp_contract_end;
    public $emp_resign_date;
    public $emp_resign_reason;
    public $emp_status;
    public $emp_emergency_contacts = [['name' => '', 'phone' => '', 'relation' => ''], ['name' => '', 'phone' => '', 'relation' => '']];
    public $emp_files = [];
    
    // For the "Use same address" checkbox
    public $use_same_address = false;

    public function mount($id = null)
    {
        // Load all provinces
        $this->provinces = Province::orderBy('province_name')->get();
        
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
            
            // Set search field values for factory and medical right
            if ($this->emp_factory_id) {
                $factory = CustomerModel::find($this->emp_factory_id);
                if ($factory) {
                    $this->factory_search = $factory->customer_name;
                }
            }
            
            if ($this->emp_medical_right) {
                $medicalRight = GlobalSetValueModel::find($this->emp_medical_right);
                if ($medicalRight) {
                    $this->medical_search = $medicalRight->value;
                }
            }

            if (is_null($this->emp_emergency_contacts)) {
                $this->emp_emergency_contacts = [['name' => '', 'phone' => '', 'relation' => ''], ['name' => '', 'phone' => '', 'relation' => '']];
            }
            
            // โหลดข้อมูลที่อยู่ตามลำดับเพื่อให้แสดงผลได้ถูกต้อง
            // โหลดที่อยู่ปัจจุบัน
            if ($this->current_province_id) {
                // โหลด amphures ของจังหวัดที่เลือก
                $province = Province::find($this->current_province_id);
                if ($province) {
                    $this->current_province_code = $province->province_code;
                    $this->currentAmphures = Amphure::where('province_code', $province->province_code)
                        ->orderBy('amphur_name')
                        ->get();
                }
                
                // โหลด districts ของอำเภอที่เลือก
                if ($this->current_amphur_id) {
                    $amphur = Amphure::find($this->current_amphur_id);
                    if ($amphur) {
                        $this->current_amphur_code = $amphur->amphur_code;
                        $this->currentDistricts = District::where('amphur_code', $amphur->amphur_code)
                            ->orderBy('district_name')
                            ->get();
                    }
                }
            }
            
            // โหลดที่อยู่ตามทะเบียนบ้าน
            if ($this->registered_province_id) {
                // โหลด amphures ของจังหวัดที่เลือก
                $province = Province::find($this->registered_province_id);
                if ($province) {
                    $this->registered_province_code = $province->province_code;
                    $this->registeredAmphures = Amphure::where('province_code', $province->province_code)
                        ->orderBy('amphur_name')
                        ->get();
                }
                
                // โหลด districts ของอำเภอที่เลือก
                if ($this->registered_amphur_id) {
                    $amphur = Amphure::find($this->registered_amphur_id);
                    if ($amphur) {
                        $this->registered_amphur_code = $amphur->amphur_code;
                        $this->registeredDistricts = District::where('amphur_code', $amphur->amphur_code)
                            ->orderBy('district_name')
                            ->get();
                    }
                }
            }
        } else {
            $this->emp_emergency_contacts = [['name' => '', 'phone' => '', 'relation' => ''], ['name' => '', 'phone' => '', 'relation' => '']];
        }
    }

    public function updatedCurrentProvinceId($value)
    {
        if ($value) {
            $province = Province::find($value);
            if ($province) {
                $this->current_province_code = $province->province_code;
                
                // Load amphures based on province code
                $this->currentAmphures = Amphure::where('province_code', $province->province_code)
                    ->orderBy('amphur_name')
                    ->get();
                
                // Reset dependent fields
                $this->current_amphur_id = null;
                $this->current_amphur_code = null;
                $this->current_district_id = null;
                $this->current_district_code = null;
                $this->current_zipcode = null;
                $this->currentDistricts = [];
                
                if ($this->use_same_address) {
                    $this->copyCurrentToRegistered();
                }
            }
        } else {
            // If no province selected, clear amphures
            $this->currentAmphures = [];
            $this->current_amphur_id = null;
            $this->current_amphur_code = null;
            $this->current_district_id = null;
            $this->current_district_code = null;
            $this->current_zipcode = null;
            $this->currentDistricts = [];
        }
    }
    
    public function updatedCurrentAmphurId($value)
    {
        if ($value) {
            $amphur = Amphure::find($value);
            if ($amphur) {
                $this->current_amphur_code = $amphur->amphur_code;
                
                // Load districts based on amphur code
                $this->currentDistricts = District::where('amphur_code', $amphur->amphur_code)
                    ->orderBy('district_name')
                    ->get();
                    
                $this->current_district_id = null;
                $this->current_district_code = null;
                $this->current_zipcode = null;
                
                if ($this->use_same_address) {
                    $this->copyCurrentToRegistered();
                }
            }
        } else {
            // If no amphur selected, clear districts
            $this->currentDistricts = [];
            $this->current_district_id = null;
            $this->current_district_code = null;
            $this->current_zipcode = null;
        }
    }
    
    public function updatedCurrentDistrictId($value)
    {
        if ($value) {
            $district = District::find($value);
            if ($district) {
                $this->current_district_code = $district->district_code;
                
                if ($district->zipcode) {
                    $this->current_zipcode = $district->zipcode;
                }
                
                if ($this->use_same_address) {
                    $this->copyCurrentToRegistered();
                }
            }
        }
    }
    
    public function updatedRegisteredProvinceId($value)
    {
        if ($value) {
            $province = Province::find($value);
            if ($province) {
                $this->registered_province_code = $province->province_code;
                
                // Load amphures based on province code
                $this->registeredAmphures = Amphure::where('province_code', $province->province_code)
                    ->orderBy('amphur_name')
                    ->get();
                    
                $this->registered_amphur_id = null;
                $this->registered_amphur_code = null;
                $this->registered_district_id = null;
                $this->registered_district_code = null;
                $this->registered_zipcode = null;
                $this->registeredDistricts = [];
            }
        } else {
            // If no province selected, clear amphures
            $this->registeredAmphures = [];
            $this->registered_amphur_id = null;
            $this->registered_amphur_code = null;
            $this->registered_district_id = null;
            $this->registered_district_code = null;
            $this->registered_zipcode = null;
            $this->registeredDistricts = [];
        }
    }
    
    public function updatedRegisteredAmphurId($value)
    {
        if ($value) {
            $amphur = Amphure::find($value);
            if ($amphur) {
                $this->registered_amphur_code = $amphur->amphur_code;
                
                // Load districts based on amphur code
                $this->registeredDistricts = District::where('amphur_code', $amphur->amphur_code)
                    ->orderBy('district_name')
                    ->get();
                    
                $this->registered_district_id = null;
                $this->registered_district_code = null;
                $this->registered_zipcode = null;
            }
        } else {
            // If no amphur selected, clear districts
            $this->registeredDistricts = [];
            $this->registered_district_id = null;
            $this->registered_district_code = null;
            $this->registered_zipcode = null;
        }
    }
    
    public function updatedRegisteredDistrictId($value)
    {
        if ($value) {
            $district = District::find($value);
            if ($district) {
                $this->registered_district_code = $district->district_code;
                
                if ($district->zipcode) {
                    $this->registered_zipcode = $district->zipcode;
                }
            }
        }
    }
    
    public function updatedUseSameAddress($value)
    {
        if ($value) {
            $this->copyCurrentToRegistered();
        }
    }
    
    public function copyCurrentToRegistered()
    {
        $this->registered_province_id = $this->current_province_id;
        $this->registered_province_code = $this->current_province_code;
        $this->registered_amphur_id = $this->current_amphur_id;
        $this->registered_amphur_code = $this->current_amphur_code;
        $this->registered_district_id = $this->current_district_id;
        $this->registered_district_code = $this->current_district_code;
        $this->registered_zipcode = $this->current_zipcode;
        $this->registered_address_details = $this->current_address_details;
        
        // Load the related data for registered address
        if ($this->registered_province_id) {
            $this->updatedRegisteredProvinceId($this->registered_province_id);
        }
        
        if ($this->registered_amphur_id) {
            $this->updatedRegisteredAmphurId($this->registered_amphur_id);
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

    public function save()
    {
        // ถ้าเลือกใช้ที่อยู่เดียวกัน ให้คัดลอกข้อมูลที่อยู่ปัจจุบันไปยังที่อยู่ตามทะเบียนบ้าน
        if ($this->use_same_address) {
            $this->copyCurrentToRegistered();
        }
        
        // คงเก็บที่อยู่แบบเดิมไว้ด้วย เพื่อความเข้ากันได้กับระบบเดิม
        // สร้างข้อความที่อยู่ปัจจุบันและที่อยู่ตามทะเบียนบ้าน
        if ($this->current_province_id && $this->current_amphur_id && $this->current_district_id) {
            $province = Province::find($this->current_province_id);
            $amphur = Amphure::find($this->current_amphur_id);
            $district = District::find($this->current_district_id);
            
            $fullAddressCurrent = $this->current_address_details ?? '';
            $fullAddressCurrent .= ' ต.' . ($district->district_name ?? '') . ' อ.' . ($amphur->amphur_name ?? '');
            $fullAddressCurrent .= ' จ.' . ($province->province_name ?? '') . ' ' . ($this->current_zipcode ?? '');
            
            $this->emp_address_current = $fullAddressCurrent;
        }
        
        if ($this->registered_province_id && $this->registered_amphur_id && $this->registered_district_id) {
            $province = Province::find($this->registered_province_id);
            $amphur = Amphure::find($this->registered_amphur_id);
            $district = District::find($this->registered_district_id);
            
            $fullAddressRegistered = $this->registered_address_details ?? '';
            $fullAddressRegistered .= ' ต.' . ($district->district_name ?? '') . ' อ.' . ($amphur->amphur_name ?? '');
            $fullAddressRegistered .= ' จ.' . ($province->province_name ?? '') . ' ' . ($this->registered_zipcode ?? '');
            
            $this->emp_address_register = $fullAddressRegistered;
        }
        
        $this->validate([
            'emp_name' => 'required|string',
            'emp_idcard' => 'required|digits:13',
            'emp_phone' => 'required',
            'current_province_id' => 'required',
            'current_amphur_id' => 'required',
            'current_district_id' => 'required',
            'current_address_details' => 'required',
            'registered_province_id' => 'required',
            'registered_amphur_id' => 'required',
            'registered_district_id' => 'required',
            'registered_address_details' => 'required',
        ]);
    
        // Handle file uploads
        if ($this->emp_files) {
            $uploadedFiles = [];
            
            if (is_array($this->emp_files)) {
                foreach ($this->emp_files as $file) {
                    if (is_object($file) && method_exists($file, 'store')) {
                        $path = $file->store('employee_files', 'public');
                        $uploadedFiles[] = $path;
                    }
                }
            }
            
            if ($this->emp_id) {
                $employee = EmployeeModel::find($this->emp_id);
                $existingFiles = $employee->emp_files ?? [];
                
                if (is_array($existingFiles)) {
                    $this->emp_files = array_merge($existingFiles, $uploadedFiles);
                } else {
                    $this->emp_files = $uploadedFiles;
                }
            } else {
                $this->emp_files = $uploadedFiles;
            }
        }
    
        EmployeeModel::updateOrCreate(
            ['id' => $this->emp_id],
            [
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
                'current_province_id' => $this->current_province_id,
                'current_province_code' => $this->current_province_code,
                'current_amphur_id' => $this->current_amphur_id,
                'current_amphur_code' => $this->current_amphur_code,
                'current_district_id' => $this->current_district_id,
                'current_district_code' => $this->current_district_code,
                'current_zipcode' => $this->current_zipcode,
                'current_address_details' => $this->current_address_details,
                'registered_province_id' => $this->registered_province_id,
                'registered_province_code' => $this->registered_province_code,
                'registered_amphur_id' => $this->registered_amphur_id,
                'registered_amphur_code' => $this->registered_amphur_code,
                'registered_district_id' => $this->registered_district_id,
                'registered_district_code' => $this->registered_district_code,
                'registered_zipcode' => $this->registered_zipcode,
                'registered_address_details' => $this->registered_address_details,
                'emp_start_date' => $this->emp_start_date,
                'emp_medical_right' => $this->emp_medical_right,
                'emp_contract_type' => $this->emp_contract_type,
                'emp_contract_start' => $this->emp_contract_start,
                'emp_contract_end' => $this->emp_contract_end,
                'emp_resign_date' => $this->emp_resign_date,
                'emp_resign_reason' => $this->emp_resign_reason,
                'emp_status' => $this->emp_status,
                'emp_emergency_contacts' => $this->emp_emergency_contacts,
                'emp_files' => $this->emp_files,
            ]
        );
    
        if ($this->emp_id) {
            session()->flash('message', 'อัพเดทข้อมูลพนักงานสำเร็จ');
        } else {
            session()->flash('message', 'เพิ่มข้อมูลพนักงานสำเร็จ');
        }
    
        return redirect()->route('employees.index');
    }

    public function updatedEmpStatus($value)
    {
        if ($value === 'เริ่มงาน') {
            $this->validate([
                'emp_code' => 'required',
                'emp_department' => 'required',
                'current_province_id' => 'required',
                'current_amphur_id' => 'required',
                'current_district_id' => 'required',
                'current_address_details' => 'required',
                'registered_province_id' => 'required',
                'registered_amphur_id' => 'required',
                'registered_district_id' => 'required',
                'registered_address_details' => 'required',
                'emp_start_date' => 'required|date',
                'emp_medical_right' => 'required',
            ]);
        } elseif ($value === 'ลาออก') {
            $this->validate([
                'emp_resign_reason' => 'required',
            ]);
        }
    }
    
    public function removeFile($index)
    {
        if (isset($this->emp_files[$index])) {
            $filePath = $this->emp_files[$index];
            
            // ลบไฟล์จริงจาก storage
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            // ลบรายการไฟล์ออกจาก array
            $files = $this->emp_files;
            unset($files[$index]);
            $this->emp_files = array_values($files);
            
            // บันทึกการเปลี่ยนแปลงในกรณีที่มี ID
            if ($this->emp_id) {
                EmployeeModel::where('id', $this->emp_id)->update(['emp_files' => $this->emp_files]);
            }
            
            session()->flash('message', 'ลบไฟล์สำเร็จ');
        }
    }
    
    // กำหนด layout สำหรับ Livewire 3
    protected $layout = 'layouts.vertical-main';
    
    public function render()
    {
        $educationOptions = GlobalSetValueModel::where('global_set_id', GlobalSetModel::where('name', 'EDUCATION')->first()->id ?? 0)->get();
        $statusOptions = GlobalSetValueModel::where('global_set_id', GlobalSetModel::where('name', 'EMP_STATUS')->first()->id ?? 0)->get();
        $recruiterOptions = GlobalSetValueModel::where('global_set_id', GlobalSetModel::where('name', 'RECRUITER')->first()->id ?? 0)->get();
        $medicalOptions = GlobalSetValueModel::where('global_set_id', GlobalSetModel::where('name', 'MEDICAL_RIGHT')->first()->id ?? 0)->get();
        $factories = CustomerModel::where('customer_status', '1')->get();

        return view('livewire.employees.employee-form', [
            'educationOptions' => $educationOptions,
            'statusOptions' => $statusOptions,
            'recruiterOptions' => $recruiterOptions,
            'medicalOptions' => $medicalOptions,
            'factories' => $factories,
            'title' => 'พนักงาน'
        ])->layout('layouts.vertical-main', ['title' => 'พนักงาน']);
    }
}
