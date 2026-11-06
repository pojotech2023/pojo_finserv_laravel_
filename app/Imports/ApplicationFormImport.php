<?php
namespace App\Imports;

use App\Models\ApplicationForm;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ApplicationFormImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new ApplicationForm([
            'name'             => $row['name'],
            'email'            => $row['email'],
            'dob'              => $row['dob'],
            'age'              => $row['age'],
            'gender'           => $row['gender'],
            'blood_group'      => $row['blood_group'],
            'photo'            => null, // Excel import has no file
            'phone'            => $row['phone'],
            'emergency_mobile' => $row['emergency_mobile'],
            'tshirt_size'      => $row['tshirt_size'],
        ]);
    }
}
