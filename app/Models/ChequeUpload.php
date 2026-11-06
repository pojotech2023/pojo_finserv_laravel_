<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChequeUpload extends Model
{
    protected $fillable = [
        'kyc_id',
        'file_path',
    ];
    public function kyc()
{
    return $this->belongsTo(KYC::class);
}

}

