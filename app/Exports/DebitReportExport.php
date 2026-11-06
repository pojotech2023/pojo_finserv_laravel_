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

class DebitReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $from_date;
    protected $to_date;
    protected $category;

    public function __construct($from_date, $to_date, $category)
    {
        $this->from_date = $from_date;
        $this->to_date   = $to_date;
        $this->category  = $category;
    }

    public function collection()
    {
        $query = BankStatement::query()->where('cr_dr', 'DR');

        switch ($this->category) {
            case 'investment': $query->where('conform_ref_no', 'like', 'INV%'); break;
            case 'loan':       $query->where('conform_ref_no', 'like', 'LOA%'); break;
            case 'excess':     $query->where('conform_ref_no', 'like', 'UW%');  break;
            case 'expanse':    $query->where('conform_ref_no', 'like', 'EXP%'); break;
        }

        if ($this->from_date && $this->to_date) {
            $query->whereBetween('date', [$this->from_date, $this->to_date]);
        }

        return $query->where('is_hidden', true)->orderBy('date', 'desc')->get();
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