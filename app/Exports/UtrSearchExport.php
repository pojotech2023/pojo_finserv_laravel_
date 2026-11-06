<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class UtrSearchExport implements WithMultipleSheets
{
    protected array  $reportData;
    protected string $utrNo;

    public function __construct(array $reportData, string $utrNo = '')
    {
        $this->reportData = $reportData;
        $this->utrNo      = $utrNo;
    }

    public function sheets(): array
    {
        return [
            new UtrFullReportSheet($this->reportData, $this->utrNo),
        ];
    }
}

// =====================================================================
// ONE Sheet — Calculation Summary + All Transactions (matches web view)
// =====================================================================
class UtrFullReportSheet implements WithTitle, FromArray, WithStyles, WithColumnWidths
{
    protected array  $d;
    protected string $utrNo;

    // Track exact row numbers for styling
    public int $summaryHeaderRow = 4;
    public int $txSectionRow     = 0;
    public int $txColHeaderRow   = 0;
    public int $txDataStartRow   = 0;
    public int $txTotalRow       = 0;
    public int $profitRow        = 0;

    public function __construct(array $reportData, string $utrNo = '')
    {
        $this->d     = $reportData;
        $this->utrNo = $utrNo;
    }

    public function title(): string { return 'Report'; }

    public function array(): array
    {
        $d        = $this->d;
        $category = $d['category'];
        $rows     = [];

        // ── ROW 1: Title ──────────────────────────
        $rows[] = ['UTR Search Report', '', '', '', '', '', '', ''];   // row 1

        // ── ROW 2: Meta ───────────────────────────
        $rows[] = [
            'UTR No: ' . $this->utrNo, '',
            'Ref No: ' . $d['ref_no'], '',
            'Category: ' . ucfirst($category), '',
            'Date: ' . now()->format('d-m-Y H:i'), '',
        ];   // row 2

        // ── ROW 3: Spacer ─────────────────────────
        $rows[] = [];   // row 3

        // ── ROW 4: Calculation Summary Header ─────
        $rows[] = ['CALCULATION SUMMARY — ' . $d['ref_no'], '', '', '', '', '', '', ''];   // row 4
        $this->summaryHeaderRow = 4;

        // ── ROWS 5+: Summary Details ──────────────
        $summaryDetailRows = [];

        if ($category === 'investment') {
            $summaryDetailRows[] = ['Investor Name',             $d['investor_name'] ?? '-',    'Percentage',  $d['percentage'] . '%', '', '', '', ''];
            $summaryDetailRows[] = ['No of Days',                $d['no_of_days'],               'Interest',    $d['interest'],          '', '', '', ''];
            $summaryDetailRows[] = ['Total Credit',              $d['credit'],                   'Total Debit', $d['debit'],             '', '', '', ''];
            $summaryDetailRows[] = ['Short / Excess Commission', $d['short_excess'],             ($d['short_excess'] >= 0 ? 'EXCESS' : 'SHORT'), '', '', '', '', ''];

        } elseif ($category === 'loan') {
            $summaryDetailRows[] = ['Bank Name',          $d['bank_name']          ?? '-', 'Staff Name',   $d['staff_name']    ?? '-', '', '', '', ''];
            $summaryDetailRows[] = ['Customer Name',      $d['customer_name']      ?? '-', 'Percentage',   $d['percentage'] . '%',     '', '', '', ''];
            $summaryDetailRows[] = ['No of Days',         $d['no_of_days'],                'Interest',     $d['interest'],             '', '', '', ''];
            $summaryDetailRows[] = ['Total Credit',       $d['credit'],                    'Total Debit',  $d['debit'],                '', '', '', ''];
            $summaryDetailRows[] = ['Received Commission',$d['received_commission'],        'Short/Excess', $d['short_excess'],         '', '', '', ''];
            $summaryDetailRows[] = ['Profit',             $d['profit'],                    '', '', '', '', '', ''];

        } else {
            $summaryDetailRows[] = ['Total Credit', $d['credit'], 'Total Debit', $d['debit'], '', '', '', ''];
            $summaryDetailRows[] = ['Profit',        $d['profit'], '',            '',          '', '', '', ''];
        }

        foreach ($summaryDetailRows as $r) { $rows[] = $r; }

        // ── Spacer ────────────────────────────────
        $rows[] = [];

        // ── Transactions Section Header ────────────
        $this->txSectionRow = count($rows) + 1;
        $rows[] = ['ALL TRANSACTIONS — Ref No: ' . $d['ref_no'], '', '', '', '', '', '', ''];

        // ── Transactions Column Header ─────────────
        $this->txColHeaderRow = count($rows) + 1;
        $rows[] = ['#', 'Date', 'UTR No', 'Conform Ref No', 'Remarks', 'Name', 'CR / DR', 'Amount (₹)'];

        // ── Transaction Rows ──────────────────────
        $this->txDataStartRow = count($rows) + 1;
        $sl      = 1;
        $crTotal = 0;
        $drTotal = 0;

        foreach ($d['details'] as $row) {
            $rows[] = [
                $sl++,
                Carbon::parse($row->date)->format('d-m-Y'),
                $row->utr_no,
                $row->conform_ref_no,
                $row->remarks,
                $row->name,
                $row->cr_dr,
                $row->amount,
            ];
            if ($row->cr_dr === 'CR') $crTotal += $row->amount;
            else                      $drTotal  += $row->amount;
        }

        // ── Totals Row ────────────────────────────
        $this->txTotalRow = count($rows) + 1;
        $rows[] = ['', '', '', '', '', 'Totals', 'CR: ' . number_format($crTotal, 2), 'DR: ' . number_format($drTotal, 2)];

        // ── Spacer + Net Profit ───────────────────
        $rows[] = [];
        $this->profitRow = count($rows) + 1;
        $rows[] = ['', '', '', '', '', '', 'Net Profit', $d['profit']];

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 28, 'B' => 20, 'C' => 26,
            'D' => 18, 'E' => 38, 'F' => 24,
            'G' => 16, 'H' => 16,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $d = $this->d;

        // ── Merges ────────────────────────────────
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:B2');
        $sheet->mergeCells('C2:D2');
        $sheet->mergeCells('E2:F2');
        $sheet->mergeCells('G2:H2');

        $sheet->mergeCells('A4:H4');
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A' . $this->txSectionRow . ':H' . $this->txSectionRow);
        $sheet->getStyle('A' . $this->txSectionRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ── Highlight searched UTR row ────────────
        $rowIdx = $this->txDataStartRow;
        foreach ($d['details'] as $row) {
            if (trim($row->utr_no) === trim($this->utrNo)) {
                $sheet->getStyle('A' . $rowIdx . ':H' . $rowIdx)
                      ->getFill()->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFFFF59D');
            }
            $rowIdx++;
        }

        // ── Totals row bold ───────────────────────
        $sheet->getStyle('F' . $this->txTotalRow . ':H' . $this->txTotalRow)->getFont()->setBold(true);

        // ── Profit row bold + large ───────────────
        $sheet->getStyle('G' . $this->profitRow . ':H' . $this->profitRow)
              ->getFont()->setBold(true)->setSize(12);

        return [
            // Row 1 — Title
            1 => [
                'font' => ['bold' => true, 'size' => 15, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1A237E']],
            ],
            // Row 2 — Meta
            2 => [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFECEFF1']],
            ],
            // Row 4 — Calculation Summary header
            4 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF0D47A1']],
            ],
            // Summary detail rows — alternating shading
            5  => ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF5F5F5']], 'font' => ['bold' => true]],
            6  => ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFFFF']], 'font' => ['bold' => true]],
            7  => ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF5F5F5']], 'font' => ['bold' => true]],
            8  => ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFFFF']], 'font' => ['bold' => true]],
            9  => ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF5F5F5']], 'font' => ['bold' => true]],
            10 => ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFFFF']], 'font' => ['bold' => true]],

            // Transactions section header
            $this->txSectionRow => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF263238']],
            ],
            // Transactions column header
            $this->txColHeaderRow => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF37474F']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Totals row
            $this->txTotalRow => [
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF263238']],
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            ],
        ];
    }
}
