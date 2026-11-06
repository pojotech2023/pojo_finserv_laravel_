<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'statement_id',
        'bank_name',
        'staff_name',
        'customer_name',
        'percentage',
        'old_percentage',
        'interest_period',
        'interest_amount',
        'old_interest_amount',
        'period_count',
        'ref_no',
        'remarks',
        'approval_status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'mismatch_cleared',
        'mismatch_cleared_at',
        'mismatch_cleared_by',
        // ── New document fields ──
        'aadhar_front',
        'aadhar_back',
        'pan_card',
        'live_photo',
        'cheque_leaf',
        'staff_id',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function statement()
    {
        return $this->belongsTo(BankStatement::class);
    }

    public function files()
    {
        return $this->hasMany(LoanFile::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function staff()
{
    return $this->belongsTo(BankStaff::class, 'staff_id');
}
}