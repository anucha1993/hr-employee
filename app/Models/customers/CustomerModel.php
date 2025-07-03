<?php

namespace App\Models\customers;

use Illuminate\Database\Eloquent\Model;
use App\Models\globalsets\GlobalSetValueModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerModel extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $casts = [
        'customer_files' => 'array',
        'customer_cid_check' => 'boolean',
    ];
    protected $fillable = ['customer_name', 'customer_taxid', 
    'customer_thefirst_contact_name',
    'customer_thefirst_contact_phone',
    'customer_thefirst_acc_name',
    'customer_thefirst_acc_phone',
    'customer_thefirst_invoice_name',
    'customer_thefirst_invoice_phone',
    'customer_salary_cut_note',
    'customer_salary_note',
    'customer_clinic_name',
    'customer_clinic_price',
    'customer_cid_check',
    'customer_employee_total_required',
    'customer_status',
    'customer_branch', 'customer_branch_name', 'customer_address_number', 'customer_address_district', 'customer_address_amphur', 'customer_address_province', 'customer_address_zipcode', 'customer_contact_name_1', 'customer_contact_phone_1', 'customer_contact_email_1', 'customer_contact_position_1', 'customer_contact_name_2', 'customer_contact_phone_2', 'customer_contact_email_2', 'customer_contact_position_2', 'customer_files', 'created_by'];

    /**
     * Get the customer files attribute.
     * Ensures this attribute is always an array, never null.
     *
     * @param  mixed  $value
     * @return array
     */
    public function getCustomerFilesAttribute($value)
    {
        return is_null($value) ? [] : $value;
    }

    public function contracts()
    {
        return $this->hasMany(contractModel::class, 'customer_id');
    }
    public function branch()
    {
        return $this->belongsTo(GlobalSetValueModel::class, 'customer_branch');
    }
    public function latestContract()
    {
        return $this->hasOne(contractModel::class, 'customer_id')->latestOfMany('contract_end_date');
    }
}
