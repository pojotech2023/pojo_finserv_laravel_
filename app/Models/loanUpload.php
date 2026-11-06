<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loanUpload extends Model
{
   protected $fillable = [
        'kyc_id',
        'loan_bill',
    ];
    public function kyc()
{
    return $this->belongsTo(KYC::class);
}

public function loanUploads()
{
    return $this->hasMany(LoanUpload::class, 'kyc_id');
}
}
