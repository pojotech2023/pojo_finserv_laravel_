<?php

namespace App\Exports;

use App\Models\ApplicationForm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApplicationFormsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ApplicationForm::select(
            'name',
            'email',
            'dob',
            'age',
            'gender',
            'blood_group',
            'photo',
            'phone',
            'emergency_mobile',
            'tshirt_size',
            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Date of Birth',
            'Age',
            'Gender',
            'Blood Group',
            'Photo',
            'Phone',
            'Emergency Mobile',
            'T-Shirt Size',
            'Submitted At',
        ];
    }
}

