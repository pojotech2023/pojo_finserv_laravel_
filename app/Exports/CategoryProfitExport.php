<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\IValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class CategoryProfitExport extends DefaultValueBinder implements
    FromArray,
    WithHeadings,
    WithTitle,
    WithEvents,
    WithColumnFormatting,
    WithCustomValueBinder,
    IValueBinder
{
    protected $data;
    protected $category;
    protected $totalCredit;
    protected $totalDebit;
    protected $totalProfit;

    // ✅ UTR columns that must always be treated as text
    protected $utrColumns = [];

    public function __construct($data, $category, $totalCredit, $totalDebit, $totalProfit)
    {
        $this->data        = $data;
        $this->category    = strtolower($category);
        $this->totalCredit = $totalCredit;
        $this->totalDebit  = $totalDebit;
        $this->totalProfit = $totalProfit;

        // Set UTR column letters based on category
        $this->utrColumns = $this->category === 'investment'
            ? ['M', 'P']   // Investment: Credit UTR = M, Debit UTR = P
            : ['N', 'Q'];  // Loan:       Credit UTR = N, Debit UTR = Q
    }

    // ✅ Custom value binder — forces UTR columns to string BEFORE Excel touches them
    public function bindValue(Cell $cell, $value): bool
    {
        $col = $cell->getColumn();

        if (in_array($col, $this->utrColumns) && $value !== null && $value !== '') {
            $cell->setValueExplicit((string)$value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function title(): string
    {
        return ucfirst($this->category) . ' Balance Report';
    }

    // ✅ Pre-format UTR columns as TEXT
    public function columnFormats(): array
    {
        if ($this->category === 'investment') {
            return [
                'M' => NumberFormat::FORMAT_TEXT,
                'P' => NumberFormat::FORMAT_TEXT,
            ];
        }

        return [
            'N' => NumberFormat::FORMAT_TEXT,
            'Q' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function headings(): array
    {
        if ($this->category === 'investment') {
            return [
                ['Category: Investment'],
                [
                    'Debit Date',
                    'Ref No',
                    'Investor Name',
                    'Customer Name',
                    'Percentage',
                    'No of Days',
                    'Interest',
                    'Actual Commission',
                    'Received Commission',
                    'Credit Date',
                    'Credit Name',
                    'Credit UTR No',
                    'Credit Remarks',
                    'Credit Amount',
                    'Debit Name',
                    'Debit UTR No',
                    'Debit Remarks',
                    'Debit Amount',
                    'Name Match',
                    'Short / Excess'
                ]
            ];
        }

        return [
            ['Category: Loan'],
            [
                'Debit Date',
                'Ref No',
                'Bank Name',
                'Staff Name',
                'Customer Name',
                'Percentage',
                'No of Days',
                'Interest',
                'Actual Commission',
                'Received Commission',
                'Credit Date',
                'Credit Name',
                'Credit UTR No',
                'Credit Remarks',
                'Credit Amount',
                'Debit Name',
                'Debit UTR No',
                'Debit Remarks',
                'Debit Amount',
                'Name Match',
                'Short / Excess'
            ]
        ];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->data as $item) {

            $credit = $item['credit_transactions']->values();
            $debit  = $item['debit_transactions']->values();

            $max = max($credit->count(), $debit->count(), 1);

            for ($i = 0; $i < $max; $i++) {

                $c = $credit->get($i);
                $d = $debit->get($i);

                $creditName = $c->name ?? '';
                $debitName  = $d->name ?? '';

                $bankStatementDebitName = '';
                if ($i === 0 && $debit->count() > 0) {
                    $bankStatementDebitName = $debit->first()->name ?? '';
                }

                // ===== NAME MATCH =====
                $nameMatch = '';
                if ($i === 0 && $creditName && $debitName) {
                    $normalize = function ($str) {
                        $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
                        $str = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $str);
                        $str = mb_strtolower($str);
                        $str = preg_replace('/[^a-z]/u', '', $str);
                        return trim($str);
                    };  
                    $nameMatch = ($normalize($creditName) === $normalize($debitName) && $normalize($creditName) !== '') ? 'yes' : 'no';
                }

                // ===== BASE COLUMNS =====
                $base = [
                    $d ? date('d-m-Y', strtotime($d->date)) : '',
                    $i === 0 ? $item['ref_no'] : '',
                ];

                // ===== CATEGORY SPECIFIC =====
                if ($this->category === 'investment') {
                    $base[] = $i === 0 ? ($item['investor_name'] ?? '') : '';
                } else {
                    $base[] = $i === 0 ? ($item['bank_name'] ?? '') : '';
                    $base[] = $i === 0 ? ($item['staff_name'] ?? '') : '';
                }

                // ===== COMMON COLUMNS =====
                $base = array_merge($base, [
                    $i === 0 ? $bankStatementDebitName : '',
                    $i === 0 ? $item['percentage'] . '%' : '',
                    $i === 0 ? $item['no_of_days'] : '',
                    $i === 0 ? (string)(round((float)$item['interest'], 2)) : '',
                    $i === 0 ? (string)(round((float)$item['interest'], 2)) : '',
                    $i === 0 ? (string)(round((float)$item['received_commission'], 2)) : '',

                    // Credit
                    $c ? date('d-m-Y', strtotime($c->date)) : '',
                    $creditName,
                    // ✅ UTR forced as string — bindValue will handle it
                    $c ? (string)($c->utrno ?? '') : '',
                    $c ? ($c->remarks ?? '') : '',
                    $c ? (string)(round((float)$c->amount, 2)) : '',

                    // Debit
                    $debitName,
                    // ✅ UTR forced as string — bindValue will handle it
                    $d ? (string)($d->utrno ?? '') : '',
                    $d ? ($d->remarks ?? '') : '',
                    $d ? (string)(round((float)$d->amount, 2)) : '',

                    $nameMatch,
                    $i === 0 ? (string)(round((float)$item['short_excess'], 2)) : '',
                ]);

                $rows[] = $base;
            }
        }

        // ===== GRAND TOTAL =====
        $rows[] = [
            'Grand Total', '', '', '', '', '', '', '',
            (string)(round((float)array_sum(array_column($this->data, 'interest')), 2)),
            (string)(round((float)array_sum(array_column($this->data, 'received_commission')), 2)),
            '', '', '', '',
            (string)(round((float)$this->totalCredit, 2)),
            '', '', '',
            (string)(round((float)$this->totalDebit, 2)),
            '',
            (string)(round((float)$this->totalProfit, 2)),
        ];

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet         = $event->sheet->getDelegate();
                $highestRow    = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // ✅ Extra safety — re-force UTR columns as text after sheet is written
                foreach ($this->utrColumns as $utrCol) {
                    for ($r = 3; $r <= $highestRow; $r++) {
                        $val = $sheet->getCell($utrCol . $r)->getValue();
                        if ($val !== null && $val !== '') {
                            $sheet->getCell($utrCol . $r)
                                ->setValueExplicit(
                                    (string)$val,
                                    DataType::TYPE_STRING
                                );
                            $sheet->getStyle($utrCol . $r)
                                ->getNumberFormat()
                                ->setFormatCode(NumberFormat::FORMAT_TEXT);
                        }
                    }
                }

                // ===== TITLE ROW =====
                $sheet->getStyle("A1:{$highestColumn}1")
                    ->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle("A1:{$highestColumn}1")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('B1D3F6');

                // ===== HEADER ROW =====
                $sheet->getStyle("A2:{$highestColumn}2")
                    ->getFont()->setBold(true);
                $sheet->getStyle("A2:{$highestColumn}2")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('0174BC');
                $sheet->getStyle("A2:{$highestColumn}2")
                    ->getFont()->getColor()->setRGB('FFFFFF');

                // ✅ Name Match column — T for Investment, U for Loan
                $nameMatchCol = $this->category === 'investment' ? 'T' : 'U';

                for ($row = 3; $row <= $highestRow; $row++) {
                    if (strtolower($sheet->getCell("{$nameMatchCol}{$row}")->getValue()) === 'yes') {
                        $sheet->getStyle("A{$row}:{$highestColumn}{$row}")
                            ->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('C6EFCE');
                    }
                }

                // ===== GRAND TOTAL ROW =====
                $sheet->getStyle("A{$highestRow}:{$highestColumn}{$highestRow}")
                    ->getFill()->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('FFEB9C');
                $sheet->getStyle("A{$highestRow}:{$highestColumn}{$highestRow}")
                    ->getFont()->setBold(true);

                // ===== AUTO SIZE =====
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}