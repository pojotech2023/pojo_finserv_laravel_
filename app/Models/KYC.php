<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\loanUpload;

class KYC extends Model
{


    protected $fillable = [
    'aadhaar_number',
    'request_id',
    'full_name',
    'dob',
    'gender',
    'care_of',
    'country',
    'state',
    'district',
    'postal_office',
    'village_town_city',
    'sub_district',
    'street',
    'house',
    'landmark',
    'zip',
    'address',
    'profile_image',
    'has_image',
    'email_hash',
    'mobile_hash',
    'face_status',
    'face_score',
    'raw_xml',
    'zip_data',
    'share_code',
    'mobile_verified',
    'aadhaar_pdf',
    'aadhaar_status',
    'pan_card_image',
    'pan_number',
    'promissory_image',
    'amount',
    'phone',
    'referel_name',
    'repayment_date',
    'gold_loan_bill',
    'repayment_time',
    'cheque',
    'aadhar_front',
    'aadhar_back',
    'borrower_signature',
    'witness_signature',
    'signed_at' ,                 
    'device_id' , 
    'ip_address',
    'pan_name',
    'service_type',
    'gold_article',
    'gross_weight',
    'net_weight',
    'gold_rate',
    'gst_percent',
    'gst_amount',
    'payment_mode',
    'utr_ref',
    'bank_acc',
    'ifsc_code',
    'bank_name_ifsc',
    'existing_bank',
    'existing_loan_acc',
    'interest_rate',
    'purpose',
    'verified_aadhaar_no',
    'verified_addhar_no',
    'verified_name',
    'aadhaar_linked_mobile',
];


public function chequeUploads()
{
    return $this->hasMany(loanUpload::class);
}
public function loanUploads()
{
    return $this->hasMany(loanUpload::class, 'kyc_id');
}
}

