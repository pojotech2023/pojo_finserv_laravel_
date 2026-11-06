<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'bank_name', 'journal', 'cluster', 'region', 'area', 'state', 'referred_by'
    ];
}
