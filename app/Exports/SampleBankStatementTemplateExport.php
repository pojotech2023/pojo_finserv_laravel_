<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class SampleBankStatementTemplateExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return new Collection([
            [
                '11/08/2026',
                '246206066662',
                'UPI/P2A/246206066662/KANNAN MA/INDB/1075L/',
                'KANNAN MA',
                '3760.00',
                'CR',
                '',
                'AXIS BANK'
            ],
            [
                '18/08/2026',
                '623012397177',
                'IMPS/P2A/623012397177/Leelavathi/X000155/ICICIBANKLTD/',
                'Leelavathi',
                '102.95',
                'DR',
                '',
                'AXIS BANK'
            ],
            [
                '07/04/2026',
                'HDFCR52026040792645123',
                'RTGS DR-ICIC0004622-SHREE KRISHNA ENTERPRISES-NETBANK, MUM-HDFCR52026040792645123',
                'SHREE KRISHNA ENTERPRISES',
                '4000000.00',
                'DR',
                '',
                'HDFC BANK'
            ],
            [
                '03/10/2026',
                'ICICR42026100300539175',
                'RTGS\ICICR42026100300539175\YESB0001149\DHANAPALG\85403354',
                'DHANAPALG',
                '305000.00',
                'DR',
                '',
                'ICICI BANK'
            ],
            [
                '03/10/2026',
                'YES0R6276188138400',
                'RTGS Cr-ICIC0000011-SHREE KRISHNA ENTERPRISES-DHANAPALG-ICICR42026100300539175',
                'SHREE KRISHNA ENTERPRISES',
                '305000.00',
                'CR',
                '',
                'YES BANK'
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Transaction Date (DD/MM/YYYY)',
            'UTR No / Reference Number',
            'Particulars / Remarks',
            'Customer Name',
            'Amount',
            'CR/DR (CR or DR)',
            'Cheque Number (Optional)',
            'Bank Name (Optional)'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E293B']
                ]
            ],
        ];
    }
}
