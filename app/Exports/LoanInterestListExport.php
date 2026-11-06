<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class LoanInterestListExport implements FromArray, WithStyles, ShouldAutoSize
{
    protected array $rows;
    protected array $utrCellMap = []; // row => col for UTR cells

    public function __construct(array $rows, array $utrCellMap = [])
    {
        $this->rows       = $rows;
        $this->utrCellMap = $utrCellMap;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $styles = [];

        // ✅ Force UTR cells as TEXT (prevent scientific notation)
        foreach ($this->utrCellMap as $rowIndex => $colIndex) {
            $cellCoord = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . $rowIndex;
            $sheet->getCell($cellCoord)
                  ->setValueExplicit(
                      $sheet->getCell($cellCoord)->getValue(),
                      DataType::TYPE_STRING
                  );
        }

        $highestRow = $sheet->getHighestRow();

        for ($r = 2; $r <= $highestRow; $r++) {
            $cellVal = $sheet->getCell('A' . $r)->getValue();

            if (str_starts_with((string)$cellVal, '__SUMMARY__')) {
                // Clean marker
                $sheet->getCell('A' . $r)->setValue(str_replace('__SUMMARY__', '', $cellVal));
                $styles[$r] = [
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '1e3a5f']],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'dbeafe'],
                    ],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ];

            } elseif (str_starts_with((string)$cellVal, '__SUBHEADER__')) {
                $sheet->getCell('A' . $r)->setValue('');
                $styles[$r] = [
                    'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '374151']],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'f3f4f6'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ];

            } elseif (str_starts_with((string)$cellVal, '__STMT__')) {
                $sheet->getCell('A' . $r)->setValue('');
                $styles[$r] = [
                    'font'      => ['size' => 9, 'color' => ['rgb' => '4b5563']],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ];
            }
        }

        // Main header row 1
        $styles[1] = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1a56db'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ];

        return $styles;
    }
}