<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorInterest extends Model
{
    use HasFactory;
    protected $fillable = [
        'statement_id',
        'utr_no',
        'ref_no',
        'conform_ref_no',
        'date',
        'amount',
        'remarks',
        'action_type',
        'old_action_type',
        'investor_id',
        'investor_name',
        'percentage',
        'investment_duration',
        'interest_amount',
    ];
}
