<?php
namespace App\Exports;

use App\Models\BankStatement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class UncategorizedCreditExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $from_date;
    protected $to_date;

    public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date   = $to_date;
    }

    public function collection()
    {
        return BankStatement::where('cr_dr', 'CR')
            ->whereBetween('date', [$this->from_date, $this->to_date])
            ->where('is_hidden', false)
            ->get();
    }

    public function headings(): array
    {
        return ['Date', 'UTR No', 'Remarks', 'Name', 'Amount', 'CR/DR', 'Ref No'];
    }

    public function map($statement): array
    {
        return [
            $statement->date->format('d-m-Y'),
            (string)($statement->utrno ?? ''),                    // ✅ string cast
            $statement->remarks,
            $statement->name,
            number_format($statement->amount, 2, '.', ''),        // ✅ no comma
            $statement->cr_dr,
            $statement->conform_ref_no,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // ✅ Force UTR column B as text
        $highestRow = $sheet->getHighestRow();
        for ($r = 2; $r <= $highestRow; $r++) {
            $sheet->getCell('B' . $r)
                  ->setValueExplicit(
                      (string)$sheet->getCell('B' . $r)->getValue(),
                      DataType::TYPE_STRING
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