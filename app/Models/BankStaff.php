<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankStaff extends Model
{
    use HasFactory;
    protected $table = 'bank_staff';

    protected $fillable = [
        'staff_name',
        'bank_name',
        'employee_id',
        'employee_designation',
        'aadhaar_number',
        'address',
        'document_path',
        'percentage',
    ];

}
