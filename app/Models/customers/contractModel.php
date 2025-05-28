<?php

namespace App\Models\customers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contractModel extends Model
{
    use HasFactory;
    protected $table = 'contracts';
   
     protected $fillable = [
        'customer_id',
        'contract_sort',
        'contract_number',
        'contract_start_date',
        'contract_end_date',
     ];

}
