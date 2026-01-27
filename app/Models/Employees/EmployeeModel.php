<?php

namespace App\Models\Employees;

use App\Models\Geo\Amphure;
use App\Models\Geo\District;
use App\Models\Geo\Province;
use App\Models\customers\CustomerModel;
use App\Models\globalsets\GlobalSetValueModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeModel extends Model
{
    use HasFactory;

    protected $table = 'employee';

    protected $fillable = [
        'emp_name',
        'emp_phone',
        'emp_recruiter_id',
        'emp_code',
        'emp_department',
        'emp_gender',
        'emp_birthdate',
        'emp_idcard',
        'emp_education',
        'emp_factory_id',
        'emp_address_current',
        'emp_address_register',
        'current_province_id',
        'current_province_code',
        'current_amphur_id',
        'current_amphur_code',
        'current_district_id',
        'current_district_code',
        'current_zipcode',
        'current_address_details',
        'registered_province_id',
        'registered_province_code',
        'registered_amphur_id',
        'registered_amphur_code',
        'registered_amphur_text',
        'registered_district_id',
        'registered_district_code',
        'registered_district_text',
        'registered_zipcode',
        'registered_address_details',
        'emp_start_date',
        'emp_medical_right',
        'emp_contract_type',
        'emp_contract_number',
        'emp_contract_start',
        'emp_contract_end',
        'emp_resign_date',
        'emp_resign_reason',
        'emp_status',
        'emp_emergency_contacts',
        'emp_files',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'emp_emergency_contacts' => 'array',
        'emp_files' => 'array',
        'emp_birthdate' => 'date',
        'emp_start_date' => 'date',
        'emp_contract_start' => 'date',
        'emp_contract_end' => 'date',
        'emp_resign_date' => 'date',
    ];
    
    /**
     * Get the current province relation
     */
    public function currentProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'current_province_id', 'id');
    }
    
    /**
     * Get the current amphure (district) relation
     */
    public function currentAmphur(): BelongsTo
    {
        return $this->belongsTo(Amphure::class, 'current_amphur_id', 'id');
    }
    
    /**
     * Get the current district (sub-district) relation
     */
    public function currentDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'current_district_id', 'id');
    }
    
    /**
     * Get the registered province relation
     */
    public function registeredProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'registered_province_id', 'id');
    }
    
    /**
     * Get the registered amphure (district) relation
     */
    public function registeredAmphur(): BelongsTo
    {
        return $this->belongsTo(Amphure::class, 'registered_amphur_id', 'id');
    }
    
    /**
     * Get the registered district (sub-district) relation
     */
    public function registeredDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'registered_district_id', 'id');
    }
    
    /**
     * Get the factory (customer) relation
     */
    public function factory(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'emp_factory_id', 'id');
    }
    
    /**
     * Get the status relation
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(GlobalSetValueModel::class, 'emp_status', 'id');
    }
    
    /**
     * Get the recruiter relation
     */
    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(GlobalSetValueModel::class, 'emp_recruiter_id', 'id');
    }
    
    /**
     * Get the user who created this employee
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    
    /**
     * Get formatted full registered address
     */
    public function getFullRegisteredAddressAttribute(): string
    {
        $address = $this->registered_address_details ?? '';
        
        // ตำบล
        if ($this->registered_district_text) {
            $address .= ' ต.' . $this->registered_district_text;
        } elseif ($this->registeredDistrict) {
            $address .= ' ต.' . $this->registeredDistrict->district_name;
        }
        
        // อำเภอ
        if ($this->registered_amphur_text) {
            $address .= ' อ.' . $this->registered_amphur_text;
        } elseif ($this->registeredAmphur) {
            $address .= ' อ.' . $this->registeredAmphur->amphur_name;
        }
        
        // จังหวัด
        if ($this->registeredProvince) {
            $address .= ' จ.' . $this->registeredProvince->province_name;
        }
        
        // รหัสไปรษณีย์
        if ($this->registered_zipcode) {
            $address .= ' ' . $this->registered_zipcode;
        }
        
        return trim($address) ?: '-';
    }
}
