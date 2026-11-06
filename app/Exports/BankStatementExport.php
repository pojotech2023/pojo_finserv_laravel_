<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class BankStatementExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $statements;

    public function __construct($statements)
    {
        $this->statements = $statements;
    }

    public function collection()
    {
        return $this->statements->map(function ($item) {
            return [
                'Date'       => $item->date->format('d-m-Y'),
                'UTR No'     => "\t" . ($item->utrno ?? ''), // ✅ force text
                'Remarks'    => $item->remarks,
                'Name'       => $item->name,
                'Amount (₹)' => number_format($item->amount, 2, '.', ''), // ✅ no comma
                'CR/DR'      => $item->cr_dr,
                'Ref No'     => $item->conform_ref_no,
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'UTR No', 'Remarks', 'Name', 'Amount (₹)', 'CR/DR', 'Ref No'];
    }

    public function styles(Worksheet $sheet): array
    {
        // ✅ Force UTR column (B) as text for all rows
        $highestRow = $sheet->getHighestRow();
        for ($r = 2; $r <= $highestRow; $r++) {
            $sheet->getCell('B' . $r)
                  ->setValueExplicit(
                      ltrim($sheet->getCell('B' . $r)->getValue(), "\t"),
                      \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                  );
        }

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1a56db'],
                ],
            ],
        ];
    }
}