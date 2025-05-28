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
    ];
     protected $fillable = [
        'customer_name',
        'customer_taxid',
        'customer_branch',
        'customer_branch_name',
        'customer_address_number',
        'customer_address_district',
        'customer_address_amphur',
        'customer_address_province',
        'customer_address_zipcode',
        'customer_files',
        'created_by',
     ];

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
