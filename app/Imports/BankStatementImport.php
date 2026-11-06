<?php

namespace App\Imports;

use App\Models\BankStatement;
use App\Models\Setting;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class BankStatementImport implements ToCollection
{
    public $selectedBankName;
    public $customStartDate;
    public $importedCount = 0;
    public $duplicateCount = 0;
    public $olderDateSkippedCount = 0;
    public $skippedCount = 0;
    public $seenInBatch = [];

    public function __construct($selectedBankName = null, $customStartDate = null)
    {
        $this->selectedBankName = $selectedBankName;
        $this->customStartDate = $customStartDate;
    }

    public function collection(Collection $rows)
    {
        $startDate = null;
        if (!empty($this->customStartDate)) {
            try {
                $startDate = Carbon::parse($this->customStartDate)->startOfDay();
            } catch (\Exception $e) {}
        }

        if (!$startDate) {
            $setting = Setting::first();
            $startDate = $setting && !empty($setting->bank_import_start_date)
                ? Carbon::parse($setting->bank_import_start_date)->startOfDay()
                : null;
        }

        // Auto-detect format mode if not explicitly provided
        $detectedBank = $this->selectedBankName ?: $this->detectBankFormat($rows);

        // Attempt dynamic table header detection
        $headerMap = $this->detectHeaderRow($rows);

        $startIndex = 0;
        if ($headerMap !== null) {
            $startIndex = $headerMap['header_index'] + 1;
        }

        foreach ($rows as $index => $row) {
            if ($index < $startIndex) {
                continue;
            }

            $rowArray = array_values($row->toArray());

            // Skip completely empty rows
            if (empty(array_filter($rowArray, function($val) {
                return $val !== null && trim((string)$val) !== '';
            }))) {
                continue;
            }

            // Check for statement summary / end of statement rows (e.g. HDFC statement footer)
            $firstCell = strtoupper(trim((string)($rowArray[0] ?? '')));
            $secondCell = strtoupper(trim((string)($rowArray[1] ?? '')));
            if (str_contains($firstCell, 'STATEMENT SUMMARY') ||
                str_contains($secondCell, 'STATEMENT SUMMARY') ||
                str_contains($firstCell, 'END OF STATEMENT') ||
                str_contains($secondCell, 'END OF STATEMENT')) {
                break; // Stop parsing remaining summary/footer rows
            }

            $parsedData = null;

            // 1. PRIMARY: If table header was detected, parse using dynamic column map
            if ($headerMap !== null) {
                $parsedData = $this->parseRowByHeader($rowArray, $headerMap, $detectedBank, $index);
            }

            // 2. SECONDARY: If header parser did not match this row, try bank-specific parsers
            if (!$parsedData && !empty($detectedBank)) {
                switch (strtoupper($detectedBank)) {
                    case 'HDFC':
                    case 'HDFC BANK':
                        $parsedData = $this->parseHdfcRow($rowArray, $index);
                        break;

                    case 'ICICI':
                    case 'ICICI BANK':
                        $parsedData = $this->parseIciciRow($rowArray, $index);
                        break;

                    case 'AXIS':
                    case 'AXIS BANK':
                        $parsedData = $this->parseAxisRow($rowArray, $index);
                        break;

                    case 'YES':
                    case 'YES BANK':
                    case 'YESBANK':
                        $parsedData = $this->parseYesBankRow($rowArray, $index);
                        break;
                }
            }

            // 3. TERTIARY: Try Master Sample Template Parser
            if (!$parsedData) {
                $parsedData = $this->parseSampleTemplateRow($rowArray, $index);
            }

            // 4. QUATERNARY: Fallback to auto / legacy row parsers
            if (!$parsedData) {
                $parsedData = $this->parseAutoRow($rowArray, $index);
                if (!$parsedData) {
                    $parsedData = $this->parseLegacyRow($rowArray, $index);
                }
            }

            if (!$parsedData) {
                $this->skippedCount++;
                continue;
            }

            $transactionDate = $parsedData['date'];
            $utrno           = $parsedData['utrno'];
            $checkNo         = $parsedData['check_no'] ?? '';
            $remarks         = $parsedData['remarks'] ?? '';
            $name            = $parsedData['name'] ?? '';
            $amount          = $parsedData['amount'];
            $cr_dr           = $parsedData['cr_dr'];
            $bankName        = $parsedData['bank_name'] ?? ($detectedBank ?: 'OTHER');

            // Date filter from Settings or Custom Input
            if ($startDate && $transactionDate->lt($startDate)) {
                $this->olderDateSkippedCount++;
                continue;
            }

            // Must have valid positive amount and CR/DR
            if ($amount <= 0 || !in_array($cr_dr, ['CR', 'DR'])) {
                $this->skippedCount++;
                continue;
            }

            // Ensure UTR is present
            if (empty($utrno)) {
                $utrno = $this->extractUtr($remarks, $checkNo, '', "row_{$index}_{$amount}");
            }

            // ==========================================
            // COMPREHENSIVE DUPLICATE ENTRY CHECK
            // ==========================================
            $cleanRemarks = trim(preg_replace('/\s+/', ' ', $remarks));
            $normRemarks = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $remarks));
            $formattedDate = $transactionDate->format('Y-m-d');
            $fmtAmount = number_format($amount, 2, '.', '');
            $cleanBank = strtoupper($bankName);

            // In-batch duplicate check (within same file/session)
            $batchKey = "{$cleanBank}|{$formattedDate}|{$fmtAmount}|{$cr_dr}|" . ($utrno ?: $normRemarks);
            if (isset($this->seenInBatch[$batchKey])) {
                $this->duplicateCount++;
                continue;
            }

            $exists = false;
            $isRealUtr = !empty($utrno) && !str_starts_with($utrno, 'REF_') && !preg_match('/^0+$/', $utrno);
            $isValidCheck = !empty($checkNo) && !preg_match('/^0+$/', $checkNo) && $checkNo !== '-';

            // 1. Match by real UTR (UTR is uniquely assigned per bank transfer)
            if ($isRealUtr) {
                $exists = BankStatement::withTrashed()
                    ->where('utrno', $utrno)
                    ->where(function($q) use ($cleanBank) {
                        if (!empty($cleanBank) && $cleanBank !== 'OTHER') {
                            $q->where('bank_name', $cleanBank);
                        }
                    })
                    ->exists();
            }

            // 2. Match by Cheque number + Date + Amount + Bank
            if (!$exists && $isValidCheck) {
                $exists = BankStatement::withTrashed()
                    ->where('date', $formattedDate)
                    ->where('amount', $amount)
                    ->where('check_no', $checkNo)
                    ->where(function($q) use ($cleanBank) {
                        if (!empty($cleanBank) && $cleanBank !== 'OTHER') {
                            $q->where('bank_name', $cleanBank);
                        }
                    })
                    ->exists();
            }

            // 3. Match by Date + Amount + CR/DR + (UTR or Remarks)
            if (!$exists) {
                $exists = BankStatement::withTrashed()
                    ->where('date', $formattedDate)
                    ->where('amount', $amount)
                    ->where('cr_dr', $cr_dr)
                    ->where(function($q) use ($cleanBank) {
                        if (!empty($cleanBank) && $cleanBank !== 'OTHER') {
                            $q->where('bank_name', $cleanBank);
                        }
                    })
                    ->where(function($q) use ($utrno, $remarks, $cleanRemarks) {
                        $q->where('utrno', $utrno)
                          ->orWhere('remarks', $remarks)
                          ->orWhere('remarks', $cleanRemarks)
                          ->orWhere('remarks', 'LIKE', '%' . substr($cleanRemarks, 0, 30) . '%');
                    })
                    ->exists();
            }

            // 4. Exact duplicate on Date + Bank + Amount + CR/DR + start of remarks
            if (!$exists && strlen($cleanRemarks) >= 10) {
                $prefixRemarks = substr($cleanRemarks, 0, 25);
                $exists = BankStatement::withTrashed()
                    ->where('date', $formattedDate)
                    ->where('amount', $amount)
                    ->where('cr_dr', $cr_dr)
                    ->where(function($q) use ($cleanBank) {
                        if (!empty($cleanBank) && $cleanBank !== 'OTHER') {
                            $q->where('bank_name', $cleanBank);
                        }
                    })
                    ->where('remarks', 'LIKE', $prefixRemarks . '%')
                    ->exists();
            }

            if ($exists) {
                $this->duplicateCount++;
                $this->seenInBatch[$batchKey] = true;
                continue; // Skip duplicate
            }

            $this->seenInBatch[$batchKey] = true;

            // Extract customer name if empty
            if (empty($name)) {
                $name = $this->extractNameFromRemarks($remarks);
            }

            // All newly imported rows start as uncategorized (is_categorized = '0')
            BankStatement::create([
                'date'           => $transactionDate->format('Y-m-d'),
                'utrno'          => $utrno,
                'check_no'       => $checkNo,
                'remarks'        => $remarks,
                'name'           => $name,
                'bank_name'      => strtoupper($bankName),
                'amount'         => $amount,
                'cr_dr'          => $cr_dr,
                'is_categorized' => '0',
            ]);

            $this->importedCount++;
        }
    }

    /**
     * Auto Detect Bank from File Header / Title Rows
     */
    private function detectBankFormat(Collection $rows)
    {
        foreach ($rows->take(35) as $row) {
            $rowStr = strtoupper(implode(' ', array_filter($row->toArray())));

            if (str_contains($rowStr, 'HDFC BANK') || str_contains($rowStr, 'HDFC000') || str_contains($rowStr, 'HDFC')) {
                return 'HDFC';
            }
            if (str_contains($rowStr, 'ICICI BANK') || str_contains($rowStr, 'ICIC000') || str_contains($rowStr, 'ICICI') || str_contains($rowStr, 'ICIC')) {
                return 'ICICI';
            }
            if (str_contains($rowStr, 'AXIS BANK') || str_contains($rowStr, 'BRANCH NAME(SOL)') || str_contains($rowStr, 'UTIB')) {
                return 'AXIS';
            }
            if (str_contains($rowStr, 'YES BANK') || str_contains($rowStr, 'YESB') || str_contains($rowStr, 'RUNNING BALANCE')) {
                return 'YES BANK';
            }
        }

        return null;
    }

    /**
     * Detect Table Header Row dynamically by inspecting column titles
     */
    private function detectHeaderRow(Collection $rows)
    {
        foreach ($rows->take(35) as $idx => $row) {
            $rowArray = array_values(array_map(function($v) {
                return strtolower(trim((string)$v));
            }, $row->toArray()));

            $hasDate = false;
            $hasRemarks = false;
            $hasAmount = false;

            $dateCols = [];
            $valDateCols = [];
            $remarksCols = [];
            $chqCols = [];
            $refCols = [];
            $withdrawalCols = [];
            $depositCols = [];
            $amountCols = [];
            $typeCols = [];
            $nameCols = [];
            $bankCols = [];

            foreach ($rowArray as $colIdx => $header) {
                if (empty($header)) continue;
                $clean = preg_replace('/[^a-z0-9\/]/', ' ', $header);
                $clean = preg_replace('/\s+/', ' ', trim($clean));

                // Date
                if (str_contains($clean, 'txn date') || str_contains($clean, 'trans date') || str_contains($clean, 'transaction date') ||
                    str_contains($clean, 'posting date') || $clean === 'date' || preg_match('/^date\b/i', $clean)) {
                    $dateCols[] = $colIdx;
                    $hasDate = true;
                } elseif (str_contains($clean, 'value date') || str_contains($clean, 'val date') || str_contains($clean, 'value dt') || str_contains($clean, 'val dt')) {
                    $valDateCols[] = $colIdx;
                    $hasDate = true;
                }

                // Remarks / Narration
                if (str_contains($clean, 'narration') || str_contains($clean, 'particulars') ||
                    str_contains($clean, 'transaction remarks') || str_contains($clean, 'remarks') ||
                    str_contains($clean, 'description') || str_contains($clean, 'transaction details')) {
                    $remarksCols[] = $colIdx;
                    $hasRemarks = true;
                }

                // Cheque No
                if (str_contains($clean, 'cheque') || str_contains($clean, 'chq') || str_contains($clean, 'check no')) {
                    $chqCols[] = $colIdx;
                }

                // Reference / UTR / Tran ID
                if (str_contains($clean, 'utr') || str_contains($clean, 'ref no') || str_contains($clean, 'reference') ||
                    str_contains($clean, 'tran id') || str_contains($clean, 'transaction id')) {
                    $refCols[] = $colIdx;
                }

                // Withdrawal / Debit
                if (str_contains($clean, 'withdrawal') || str_contains($clean, 'debit') || preg_match('/\bdr\b/i', $clean)) {
                    if (!str_contains($clean, 'cr/dr') && !str_contains($clean, 'dr/cr') && !str_contains($clean, 'cr or dr')) {
                        $withdrawalCols[] = $colIdx;
                        $hasAmount = true;
                    }
                }

                // Deposit / Credit
                if (str_contains($clean, 'deposit') || str_contains($clean, 'credit') || preg_match('/\bcr\b/i', $clean)) {
                    if (!str_contains($clean, 'cr/dr') && !str_contains($clean, 'dr/cr') && !str_contains($clean, 'cr or dr')) {
                        $depositCols[] = $colIdx;
                        $hasAmount = true;
                    }
                }

                // Combined Amount
                if (str_contains($clean, 'amount')) {
                    if (!str_contains($clean, 'withdrawal') && !str_contains($clean, 'deposit') && !str_contains($clean, 'debit') && !str_contains($clean, 'credit')) {
                        $amountCols[] = $colIdx;
                        $hasAmount = true;
                    }
                }

                // Cr/Dr Type
                if (str_contains($clean, 'cr/dr') || str_contains($clean, 'dr/cr') || $clean === 'type' || str_contains($clean, 'cr or dr')) {
                    $typeCols[] = $colIdx;
                }

                // Customer / Party Name
                if (str_contains($clean, 'name') && !str_contains($clean, 'bank')) {
                    $nameCols[] = $colIdx;
                }

                // Bank Name
                if (str_contains($clean, 'bank name')) {
                    $bankCols[] = $colIdx;
                }
            }

            if ($hasDate && $hasRemarks && $hasAmount) {
                return [
                    'header_index'    => $idx,
                    'date_col'        => !empty($dateCols) ? $dateCols[0] : (!empty($valDateCols) ? $valDateCols[0] : null),
                    'val_date_col'    => !empty($valDateCols) ? $valDateCols[0] : null,
                    'remarks_col'     => !empty($remarksCols) ? $remarksCols[0] : null,
                    'chq_col'         => !empty($chqCols) ? $chqCols[0] : null,
                    'ref_col'         => !empty($refCols) ? $refCols[0] : null,
                    'withdrawal_col'  => !empty($withdrawalCols) ? $withdrawalCols[0] : null,
                    'deposit_col'     => !empty($depositCols) ? $depositCols[0] : null,
                    'amount_col'      => !empty($amountCols) ? $amountCols[0] : null,
                    'type_col'        => !empty($typeCols) ? $typeCols[0] : null,
                    'name_col'        => !empty($nameCols) ? $nameCols[0] : null,
                    'bank_col'        => !empty($bankCols) ? $bankCols[0] : null,
                ];
            }
        }

        return null;
    }

    /**
     * Parse row using dynamic column mapping detected from the table header
     */
    private function parseRowByHeader(array $row, array $headerMap, $defaultBank = 'OTHER', $rowIndex = 0)
    {
        // 1. Transaction Date
        $date = null;
        if ($headerMap['date_col'] !== null && isset($row[$headerMap['date_col']])) {
            $date = $this->parseDate($row[$headerMap['date_col']]);
        }
        if (!$date && $headerMap['val_date_col'] !== null && isset($row[$headerMap['val_date_col']])) {
            $date = $this->parseDate($row[$headerMap['val_date_col']]);
        }
        if (!$date) {
            return null;
        }

        // 2. Remarks / Narration
        $remarks = '';
        if ($headerMap['remarks_col'] !== null && isset($row[$headerMap['remarks_col']])) {
            $remarks = trim((string)$row[$headerMap['remarks_col']]);
        }
        if (empty($remarks) || stripos($remarks, 'OPENING BALANCE') !== false || stripos($remarks, 'CLOSING BALANCE') !== false || stripos($remarks, 'STATEMENT SUMMARY') !== false) {
            return null;
        }

        // 3. Cheque & Reference numbers
        $checkNo = ($headerMap['chq_col'] !== null && isset($row[$headerMap['chq_col']]))
            ? preg_replace('/\s+/', '', trim((string)$row[$headerMap['chq_col']]))
            : '';
        $refNo = ($headerMap['ref_col'] !== null && isset($row[$headerMap['ref_col']]))
            ? preg_replace('/\s+/', '', trim((string)$row[$headerMap['ref_col']]))
            : '';

        // 4. Amount and CR/DR calculation
        $amount = 0;
        $cr_dr  = '';

        // Check dual column Withdrawal / Deposit first
        if ($headerMap['withdrawal_col'] !== null || $headerMap['deposit_col'] !== null) {
            $wStr = $headerMap['withdrawal_col'] !== null ? trim((string)($row[$headerMap['withdrawal_col']] ?? '0')) : '0';
            $dStr = $headerMap['deposit_col'] !== null ? trim((string)($row[$headerMap['deposit_col']] ?? '0')) : '0';

            $w = (float) str_replace(['INR', ',', ' '], '', $wStr);
            $d = (float) str_replace(['INR', ',', ' '], '', $dStr);

            if ($d > 0) {
                $amount = $d;
                $cr_dr  = 'CR';
            } elseif ($w > 0) {
                $amount = $w;
                $cr_dr  = 'DR';
            }
        }

        // Fallback to combined Amount + Type column
        if ($amount <= 0 && $headerMap['amount_col'] !== null && isset($row[$headerMap['amount_col']])) {
            $rawAmount = trim((string)$row[$headerMap['amount_col']]);
            if (str_contains(strtoupper($rawAmount), 'CR')) $cr_dr = 'CR';
            elseif (str_contains(strtoupper($rawAmount), 'DR')) $cr_dr = 'DR';

            $amt = (float) str_replace(['INR', ',', 'CR', 'DR', 'cr', 'dr', ' '], '', $rawAmount);
            if ($amt > 0) {
                $amount = $amt;
                if (empty($cr_dr) && $headerMap['type_col'] !== null && isset($row[$headerMap['type_col']])) {
                    $t = strtoupper(trim((string)$row[$headerMap['type_col']]));
                    if (in_array($t, ['CR', 'DR'])) {
                        $cr_dr = $t;
                    }
                }
            }
        }

        if ($amount <= 0 || empty($cr_dr)) {
            return null;
        }

        // 5. UTR extraction
        $seed = "row_{$rowIndex}_{$date->format('Ymd')}_{$amount}_{$cr_dr}";
        $utr = $this->extractUtr($remarks, $checkNo, $refNo, $seed);

        // 6. Bank Name
        $bank = $defaultBank ?: 'OTHER';
        if ($headerMap['bank_col'] !== null && !empty($row[$headerMap['bank_col']])) {
            $b = trim((string)$row[$headerMap['bank_col']]);
            if (!empty($b)) $bank = $b;
        }

        // 7. Customer Name
        $name = '';
        if ($headerMap['name_col'] !== null && !empty($row[$headerMap['name_col']])) {
            $name = trim((string)$row[$headerMap['name_col']]);
        }
        if (empty($name)) {
            $name = $this->extractNameFromRemarks($remarks);
        }

        return [
            'date'      => $date,
            'utrno'     => $utr,
            'check_no'  => $checkNo,
            'remarks'   => $remarks,
            'name'      => $name,
            'amount'    => $amount,
            'cr_dr'     => $cr_dr,
            'bank_name' => $bank,
        ];
    }

    /**
     * Parse Master Sample Template Row
     * Header: Transaction Date, UTR No, Particulars/Remarks, Customer Name, Amount, CR/DR, Cheque No, Bank Name
     */
    private function parseSampleTemplateRow(array $row, $rowIndex = 0)
    {
        $dateStr = trim((string)($row[0] ?? ''));
        $date = $this->parseDate($dateStr);
        if (!$date) return null;

        $utrno = preg_replace('/\s+/', '', trim((string)($row[1] ?? '')));
        $remarks = trim((string)($row[2] ?? ''));
        $name = trim((string)($row[3] ?? ''));

        $rawAmount = str_replace(['INR', ',', ' '], '', trim((string)($row[4] ?? '0')));
        $amount = (float) $rawAmount;

        $cr_dr = strtoupper(trim((string)($row[5] ?? '')));
        if (!in_array($cr_dr, ['CR', 'DR']) || $amount <= 0) {
            return null;
        }

        $checkNo = preg_replace('/\s+/', '', trim((string)($row[6] ?? '')));
        $bankName = strtoupper(trim((string)($row[7] ?? 'OTHER')));
        if (empty($bankName)) $bankName = 'OTHER';

        if (empty($name)) {
            $name = $this->extractNameFromRemarks($remarks);
        }

        if (empty($utrno)) {
            $utrno = $this->extractUtr($remarks, $checkNo, '', "tpl_{$rowIndex}_{$amount}");
        }

        return [
            'date'      => $date,
            'utrno'     => $utrno,
            'check_no'  => $checkNo,
            'remarks'   => $remarks,
            'name'      => $name,
            'amount'    => $amount,
            'cr_dr'     => $cr_dr,
            'bank_name' => $bankName,
        ];
    }

    /**
     * Parse HDFC Bank Row (Adaptive Fallback)
     */
    private function parseHdfcRow(array $row, $rowIndex = 0)
    {
        // Try col 0 or col 1 for date (if col 0 is Sl No)
        $date = $this->parseDate($row[0] ?? null);
        $offset = 0;
        if (!$date && isset($row[1])) {
            $date = $this->parseDate($row[1]);
            if ($date) $offset = 1;
        }
        if (!$date) return null;

        $remarks = trim((string)($row[1 + $offset] ?? ''));
        if (empty($remarks) || stripos($remarks, 'STATEMENT SUMMARY') !== false || stripos($remarks, 'OPENING BALANCE') !== false || stripos($remarks, 'CLOSING BALANCE') !== false) {
            return null;
        }

        $chqRef = preg_replace('/\s+/', '', trim((string)($row[2 + $offset] ?? '')));

        // In HDFC, withdrawal & deposit are typically at index (4,5) or (3,4) depending on Value Date presence
        $w1 = (float) str_replace(['INR', ',', ' '], '', trim((string)($row[3 + $offset] ?? '0')));
        $d1 = (float) str_replace(['INR', ',', ' '], '', trim((string)($row[4 + $offset] ?? '0')));
        $w2 = (float) str_replace(['INR', ',', ' '], '', trim((string)($row[4 + $offset] ?? '0')));
        $d2 = (float) str_replace(['INR', ',', ' '], '', trim((string)($row[5 + $offset] ?? '0')));

        $amount = 0;
        $cr_dr  = '';

        if ($d2 > 0) {
            $amount = $d2;
            $cr_dr  = 'CR';
        } elseif ($w2 > 0) {
            $amount = $w2;
            $cr_dr  = 'DR';
        } elseif ($d1 > 0) {
            $amount = $d1;
            $cr_dr  = 'CR';
        } elseif ($w1 > 0) {
            $amount = $w1;
            $cr_dr  = 'DR';
        }

        if ($amount <= 0 || empty($cr_dr)) return null;

        $utrno = $this->extractUtr($remarks, $chqRef, '', "hdfc_{$rowIndex}_{$amount}");

        return [
            'date'      => $date,
            'utrno'     => $utrno,
            'check_no'  => $chqRef,
            'remarks'   => $remarks,
            'name'      => $this->extractNameFromRemarks($remarks),
            'amount'    => $amount,
            'cr_dr'     => $cr_dr,
            'bank_name' => 'HDFC',
        ];
    }

    /**
     * Parse ICICI Bank Row (Adaptive Fallback)
     */
    private function parseIciciRow(array $row, $rowIndex = 0)
    {
        // Try col 2 (Txn Date), col 1 (Value Date), or col 0 (Date)
        $date = null;
        $dateCol = null;
        foreach ([2, 1, 0, 3] as $c) {
            if (isset($row[$c])) {
                $d = $this->parseDate($row[$c]);
                if ($d) {
                    $date = $d;
                    $dateCol = $c;
                    break;
                }
            }
        }
        if (!$date) return null;

        // Find remarks column
        $remarks = '';
        foreach ([5, 4, 3, 2] as $c) {
            if ($c !== $dateCol && isset($row[$c])) {
                $txt = trim((string)$row[$c]);
                if (strlen($txt) > 5 && !is_numeric($txt)) {
                    $remarks = $txt;
                    break;
                }
            }
        }
        if (empty($remarks) || stripos($remarks, 'OPENING BALANCE') !== false || stripos($remarks, 'CLOSING BALANCE') !== false) {
            return null;
        }

        $checkNo = preg_replace('/\s+/', '', trim((string)($row[4] ?? $row[3] ?? $row[1] ?? '')));

        // Check amounts
        $amount = 0;
        $cr_dr  = '';

        // Check for separate withdrawal / deposit columns
        $w = (float) str_replace(['INR', ',', ' '], '', trim((string)($row[5] ?? '0')));
        $d = (float) str_replace(['INR', ',', ' '], '', trim((string)($row[6] ?? '0')));

        if ($d > 0) {
            $amount = $d;
            $cr_dr  = 'CR';
        } elseif ($w > 0) {
            $amount = $w;
            $cr_dr  = 'DR';
        }

        // Single amount + CR/DR type column (e.g. ICICI CIB format col 7 and col 6)
        if ($amount <= 0 && isset($row[7])) {
            $rawAmount = trim((string)$row[7]);
            $typeStr   = strtoupper(trim((string)($row[6] ?? '')));

            if (str_contains($rawAmount, 'CR')) $cr_dr = 'CR';
            elseif (str_contains($rawAmount, 'DR')) $cr_dr = 'DR';
            elseif (in_array($typeStr, ['CR', 'DR'])) $cr_dr = $typeStr;

            $amt = (float) str_replace(['INR', ',', 'CR', 'DR', ' '], '', $rawAmount);
            if ($amt > 0 && !empty($cr_dr)) {
                $amount = $amt;
            }
        }

        if ($amount <= 0 || empty($cr_dr)) return null;

        $txnId = trim((string)($row[1] ?? $row[0] ?? ''));
        $utrno = $this->extractUtr($remarks, $checkNo, $txnId, "icici_{$rowIndex}_{$amount}");

        return [
            'date'      => $date,
            'utrno'     => $utrno,
            'check_no'  => $checkNo,
            'remarks'   => $remarks,
            'name'      => $this->extractNameFromRemarks($remarks),
            'amount'    => $amount,
            'cr_dr'     => $cr_dr,
            'bank_name' => 'ICICI',
        ];
    }

    /**
     * Parse AXIS Bank Row
     */
    private function parseAxisRow(array $row, $rowIndex = 0)
    {
        $dateStr = trim((string)($row[1] ?? $row[0] ?? ''));
        $date = $this->parseDate($dateStr);
        if (!$date) return null;

        $remarks = trim((string)($row[3] ?? ''));
        if (empty($remarks) || stripos($remarks, 'OPENING BALANCE') !== false || stripos($remarks, 'CLOSING BALANCE') !== false) {
            return null;
        }

        $rawAmount = str_replace(['INR', ',', ' '], '', trim((string)($row[4] ?? '0')));
        $amount = (float) $rawAmount;

        $cr_dr = strtoupper(trim((string)($row[5] ?? '')));
        if (!in_array($cr_dr, ['CR', 'DR'])) {
            return null;
        }

        $checkNo = preg_replace('/\s+/', '', trim((string)($row[7] ?? '')));
        $utrno   = $this->extractUtr($remarks, $checkNo, (string)($row[0] ?? ''), "axis_{$rowIndex}_{$amount}");

        return [
            'date'      => $date,
            'utrno'     => $utrno,
            'check_no'  => $checkNo,
            'remarks'   => $remarks,
            'name'      => $this->extractNameFromRemarks($remarks),
            'amount'    => $amount,
            'cr_dr'     => $cr_dr,
            'bank_name' => 'AXIS',
        ];
    }

    /**
     * Parse YES BANK Row
     */
    private function parseYesBankRow(array $row, $rowIndex = 0)
    {
        $dateStr = trim((string)($row[0] ?? ''));
        $date = $this->parseDate($dateStr);
        if (!$date) return null;

        $remarks = trim((string)($row[2] ?? ''));
        if (empty($remarks) || stripos($remarks, 'OPENING BALANCE') !== false || stripos($remarks, 'CLOSING BALANCE') !== false) {
            return null;
        }

        $utrno = preg_replace('/\s+/', '', trim((string)($row[3] ?? '')));

        $withdrawal = (float) str_replace(['INR', ',', ' '], '', trim((string)($row[4] ?? '0')));
        $deposit    = (float) str_replace(['INR', ',', ' '], '', trim((string)($row[5] ?? '0')));

        $amount = 0;
        $cr_dr  = '';

        if ($deposit > 0) {
            $amount = $deposit;
            $cr_dr  = 'CR';
        } elseif ($withdrawal > 0) {
            $amount = $withdrawal;
            $cr_dr  = 'DR';
        }

        if ($amount <= 0 || empty($cr_dr)) return null;

        if (empty($utrno)) {
            $utrno = $this->extractUtr($remarks, '', '', "yes_{$rowIndex}_{$amount}");
        }

        return [
            'date'      => $date,
            'utrno'     => $utrno,
            'check_no'  => '',
            'remarks'   => $remarks,
            'name'      => $this->extractNameFromRemarks($remarks),
            'amount'    => $amount,
            'cr_dr'     => $cr_dr,
            'bank_name' => 'YES BANK',
        ];
    }

    /**
     * Auto Row parser that tries matching rows dynamically
     */
    private function parseAutoRow(array $row, $rowIndex = 0)
    {
        if (count($row) >= 4) {
            if ($result = $this->parseSampleTemplateRow($row, $rowIndex)) return $result;
            if ($result = $this->parseHdfcRow($row, $rowIndex)) return $result;
            if ($result = $this->parseIciciRow($row, $rowIndex)) return $result;
            if ($result = $this->parseAxisRow($row, $rowIndex)) return $result;
            if ($result = $this->parseYesBankRow($row, $rowIndex)) return $result;
        }
        return null;
    }

    /**
     * Legacy fixed column parser
     */
    private function parseLegacyRow(array $row, int $index)
    {
        if ($index < 27) return null;

        $transactionDate = null;
        try {
            if (isset($row[8]) && is_numeric($row[8])) {
                $transactionDate = Carbon::instance(ExcelDate::excelToDateTimeObject($row[8]))->startOfDay();
            } elseif (!empty($row[8])) {
                $transactionDate = $this->parseDate($row[8]);
            }
        } catch (\Exception $e) {
            return null;
        }

        if (!$transactionDate) return null;

        $utrno   = isset($row[20]) ? preg_replace('/\s+/', '', trim((string)$row[20])) : '';
        $remarks = isset($row[44]) ? trim((string)$row[44]) : '';
        $checkNo = isset($row[31]) ? preg_replace('/\s+/', '', trim((string)$row[31])) : '';

        $creditAmount = isset($row[62]) ? (float) str_replace(['INR', ',', "\n", ' '], '', (string)$row[62]) : 0;
        $debitAmount  = isset($row[70]) ? (float) str_replace(['INR', ',', "\n", ' '], '', (string)$row[70]) : 0;

        $amount = 0;
        $cr_dr  = '';

        if ($creditAmount > 0) {
            $amount = $creditAmount;
            $cr_dr  = 'CR';
        } elseif ($debitAmount > 0) {
            $amount = $debitAmount;
            $cr_dr  = 'DR';
        }

        if ($amount <= 0 || empty($cr_dr)) return null;

        if (empty($utrno)) {
            $utrno = $this->extractUtr($remarks, $checkNo, '', "legacy_{$index}_{$amount}");
        }

        return [
            'date'      => $transactionDate,
            'utrno'     => $utrno,
            'check_no'  => $checkNo,
            'remarks'   => $remarks,
            'name'      => $this->extractNameFromRemarks($remarks),
            'amount'    => $amount,
            'cr_dr'     => $cr_dr,
            'bank_name' => 'OTHER',
        ];
    }

    /**
     * Date Parser Supporting Multiple Standard Formats, Month names, & Excel serials
     */
    private function parseDate($dateVal)
    {
        if (empty($dateVal)) return null;

        // Excel serial date check (only valid dates between 1982 and 2091)
        if (is_numeric($dateVal) && $dateVal > 30000 && $dateVal < 70000) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($dateVal))->startOfDay();
            } catch (\Exception $e) {}
        }

        $dateStr = trim((string)$dateVal);
        if (preg_match('/^(\d{1,4}[-\/\.][A-Za-z0-9]{1,9}[-\/\.]\d{2,4})/', $dateStr, $m)) {
            $dateOnly = $m[1];
        } else {
            $dateOnly = explode(' ', $dateStr)[0];
        }

        $formats = [
            'd/m/Y', 'd-m-Y', 'd.m.Y',
            'd/M/Y', 'd-M-Y', 'd M Y', 'd F Y',
            'd/m/y', 'd-m-y',
            'd/M/y', 'd-M-y',
            'Y-m-d', 'Y/m/d',
            'm/d/Y', 'm-d-Y'
        ];

        foreach ($formats as $fmt) {
            try {
                $d = Carbon::createFromFormat($fmt, $dateOnly)->startOfDay();
                if ($d->year < 100) {
                    $d->addYears(2000);
                }
                return $d;
            } catch (\Exception $e) {}
        }

        try {
            $d = Carbon::parse($dateOnly)->startOfDay();
            if ($d->year < 100) $d->addYears(2000);
            if ($d->year >= 1990 && $d->year <= 2099) {
                return $d;
            }
        } catch (\Exception $e) {}

        return null;
    }

    /**
     * Extract UTR or Reference Number from string
     */
    private function extractUtr($remarks, $checkNo = '', $fallback = '', $rowSeed = '')
    {
        $cleanCheck = (!empty($checkNo) && !preg_match('/^0+$/', trim($checkNo)) && trim($checkNo) !== '-') ? trim($checkNo) : '';
        $cleanFallback = (!empty($fallback) && !preg_match('/^0+$/', trim($fallback)) && trim($fallback) !== '-') ? trim($fallback) : '';

        // 1. Check for specific UTR patterns in remarks:
        if (preg_match('/(?:RTGS|NEFT|IMPS|UPI|INF|INFT|BIL)[\/\\:\-_\s]+([A-Z0-9]{9,25})/i', $remarks, $m)) {
            return $m[1];
        }
        // 2. Generic alphanumeric UTR (12-25 chars)
        if (preg_match('/[A-Z0-9]{12,25}/i', $remarks, $m)) {
            return $m[0];
        }
        // 3. Fallback to clean fallback (e.g. Tran Id or ref column)
        if (!empty($cleanFallback)) {
            return $cleanFallback;
        }
        // 4. Fallback to cheque number
        if (!empty($cleanCheck)) {
            return $cleanCheck;
        }
        // 5. Unique deterministic hash (strip variable row index so same transaction across different files has identical UTR hash)
        $cleanSeed = preg_replace('/(row|tpl|hdfc|icici|axis|yes|legacy)_\d+_/', '', $rowSeed);
        $normRemarks = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $remarks));
        return 'REF_' . substr(md5($normRemarks . '_' . $cleanSeed), 0, 12);
    }

    /**
     * Extract Name from Remarks String
     */
    private function extractNameFromRemarks($remarks)
    {
        if (empty($remarks)) return '';

        $clean = preg_replace('/\s+/', ' ', $remarks);
        $excluded = [
            'UPI', 'P2A', 'P2M', 'RTGS', 'NEFT', 'IMPS', 'INB', 'TRF', 'TRANSFER',
            'URGENT', 'NETBANK', 'MUM', 'P2V', 'YESB', 'HDFC', 'ICIC', 'ICICI',
            'UTIB', 'INDB', 'FDRL', 'MMT', 'IFB', 'INF', 'INFT', 'BIL', 'NFT',
            'CMS', 'CMSPOS', 'BY MLE', 'MLE', 'FAST', 'AXIS BANK', 'YES BANK',
            'HDFC BANK', 'ICICI BANK', 'CANARA BANK', 'STATE BANK OF INDIA',
            'TEST123', 'TEST', 'CHEQUE', 'CHQ', 'CLR', 'CLEARING'
        ];

        $parts = preg_split('/[\/\\\\]/', $clean);
        if (count($parts) > 1) {
            foreach ($parts as $part) {
                $p = trim($part);
                if (empty($p)) continue;
                $pUpper = strtoupper($p);

                // Skip if in excluded list
                if (in_array($pUpper, $excluded)) continue;

                // Skip if numeric or account number
                if (preg_match('/^[0-9\s]+$/', $p)) continue;

                // Skip if looks like IFSC code (e.g. DBSS0IN0191, HDFC0001588)
                if (preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/i', $p)) continue;

                // Skip if looks like a reference / UTR (e.g. ICICR42026100100516084 or S71878227)
                if (preg_match('/^[A-Z0-9]{15,25}$/i', $p)) continue;

                // Skip if contains only bank name indicators
                if (preg_match('/^(axis|icici|hdfc|yes|canara|federal)\s*bank$/i', $p)) continue;

                // Skip very short words (<= 2 chars)
                if (strlen($p) <= 2) continue;

                return $p;
            }
        }

        if (str_contains($clean, '-')) {
            $parts = explode('-', $clean);
            foreach (array_slice($parts, 1) as $part) {
                $p = trim($part);
                if (empty($p)) continue;
                $pUpper = strtoupper($p);
                if (in_array($pUpper, $excluded)) continue;
                if (preg_match('/^[0-9\s]+$/', $p)) continue;
                if (preg_match('/^[A-Z]{4}0[A-Z0-9]{6}$/i', $p)) continue;
                if (preg_match('/^[A-Z0-9]{15,25}$/i', $p)) continue;
                if (strlen($p) <= 2) continue;
                return $p;
            }
        }

        return $clean;
    }
}
