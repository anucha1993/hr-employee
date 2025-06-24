<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    'emp_start_date',
    'emp_medical_right',
    'emp_contract_type',
    'emp_contract_start',
    'emp_contract_end',
    'emp_resign_date',
    'emp_resign_reason',
    'emp_status',
    'emp_emergency_contacts',
    'emp_files',
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
} 
