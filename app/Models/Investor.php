<?php

namespace App\Models;
use App\Models\BankStatement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'investment_duration', 'investment_duration_value', 'investment_duration_unit',
        'percentage', 'payout_time', 'account_number', 'ifsc_code', 'branch_name',
        'address', 'gst_number', 'interest_amount', 'interest_status', 'statement_id',
        'ref_no', 'kyc_file_path', 'duration_status', 'remarks', 'received_commission', 'interest_balance'
    ];

public function bankStatement()
{
    return $this->belongsTo(BankStatement::class, 'statement_id', 'id');
}
public function statement()
{
    return $this->belongsTo(BankStatement::class, 'statement_id', 'id');
}
}
