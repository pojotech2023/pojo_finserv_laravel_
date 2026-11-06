<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\BankStatementImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UncategorizedCreditExport;
use App\Exports\UncategorizedDebitExport;
use App\Exports\BankReportExport;
use App\Exports\DebitReportExport;
use App\Exports\CreditReportExport;
use App\Exports\BankStatementExport;
use App\Exports\CategoryProfitExport;
use App\Models\BankStatement;
use App\Models\Investor;
use App\Models\Loan;
use App\Models\Excess;
use App\Models\Expanses;
use App\Models\BankStaff;
use App\Exports\SampleBankStatementTemplateExport;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Exports\UtrSearchExport;

use Illuminate\Support\Facades\Auth;


class BankStatementController extends Controller
{
    public function getform(Request $request)
    {
        // Auto-fix OD INTEREST records where conform_ref_no is empty but ref_no exists
        BankStatement::whereIn('action_type', ['OD INTEREST', 'OD'])
            ->whereNull('conform_ref_no')
            ->whereNotNull('ref_no')
            ->get()
            ->each(function ($stmt) {
                $stmt->conform_ref_no = $stmt->ref_no;
                $stmt->save();
            });

      $query = BankStatement::query()
    ->where('is_hidden', false)
    ->where(function ($q) {
        $q->whereNull('conform_ref_no')
          ->orWhere('conform_ref_no', 'not like', 'INV%');
    });

        // Name filter
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Bank Name filter
        if ($request->filled('bank_name')) {
            $query->where('bank_name', $request->bank_name);
        }

        // Ref No filter (UTR → confirm_ref_no)
        if ($request->filled('ref_no')) {
            $query->where('conform_ref_no', 'like', '%' . $request->ref_no . '%');
        }
        if ($request->filled('utrno')) {
            $query->where('utrno', 'like', '%' . $request->utrno . '%');
        }

        // Date filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $statements = $query->orderBy('date', 'asc')->paginate(10);
        $statements->appends($request->all());

        $pendingCount = Investor::where('interest_status', 'pending')->count();
        return view('accounts-admin.account-statement.account-statement-import', compact('statements', 'pendingCount'));
    }

    public function softDeleteSingle($id)
    {
        $statement = BankStatement::findOrFail($id);
        $statement->delete();

        return redirect()->back()->with('success', 'Record soft deleted successfully.');
    }

    public function deleteByDateRange(Request $request)
    {
        $request->validate([
            'delete_from_date' => 'required|date',
            'delete_to_date'   => 'required|date|after_or_equal:delete_from_date',
        ], [
            'delete_from_date.required'      => 'From date is required.',
            'delete_to_date.required'        => 'To date is required.',
            'delete_to_date.after_or_equal'  => 'To date must be after or equal to From date.',
        ]);

        $fromDate = $request->delete_from_date;
        $toDate   = $request->delete_to_date;

        $count = BankStatement::whereBetween('date', [$fromDate, $toDate])->count();

        if ($count === 0) {
            return redirect()->back()
                ->with('error', 'No active records found in the selected date range.')
                ->withInput();
        }

        BankStatement::whereBetween('date', [$fromDate, $toDate])->delete();

        return redirect()->back()
            ->with('success', "{$count} record(s) soft deleted from {$fromDate} to {$toDate} successfully.");
    }

    public function trashedRecords(Request $request)
    {
        $query = BankStatement::onlyTrashed();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $statements = $query->orderBy('date', 'asc')->paginate(10);
        $statements->appends($request->all());

        return view('accounts-admin.account-statement.test', compact('statements'));
        //return view('accounts-admin.account-statement.trashed-statements', compact('statements'));
    }

    public function restoreRecord($id)
    {
        $statement = BankStatement::onlyTrashed()->findOrFail($id);
        $statement->restore();

        return redirect()->back()->with('success', 'Record restored successfully.');
    }

    public function restoreByDateRange(Request $request)
    {
        $request->validate([
            'restore_from_date' => 'required|date',
            'restore_to_date'   => 'required|date|after_or_equal:restore_from_date',
        ], [
            'restore_from_date.required'      => 'From date is required.',
            'restore_to_date.required'        => 'To date is required.',
            'restore_to_date.after_or_equal'  => 'To date must be after or equal to From date.',
        ]);

        $count = BankStatement::onlyTrashed()
                    ->whereBetween('date', [$request->restore_from_date, $request->restore_to_date])
                    ->count();

        if ($count === 0) {
            return redirect()->back()
                ->with('error', 'No deleted records found in the selected date range.')
                ->withInput();
        }

        BankStatement::onlyTrashed()
            ->whereBetween('date', [$request->restore_from_date, $request->restore_to_date])
            ->restore();

        return redirect()->back()
            ->with('success', "{$count} record(s) restored successfully.");
    }

    public function forceDeleteRecord($id)
    {
        $statement = BankStatement::onlyTrashed()->findOrFail($id);
        $statement->forceDelete();

        return redirect()->back()->with('success', 'Record permanently deleted.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
            'from_date' => 'nullable|date'
        ]);

        $bankName = $request->input('bank_name');
        $fromDate = $request->input('from_date');

        try {
            $import = new BankStatementImport($bankName, $fromDate);
            Excel::import($import, $request->file('file'));

            $msgParts = [];
            if ($import->importedCount > 0) {
                $msgParts[] = "Successfully imported {$import->importedCount} transaction(s).";
            }
            if ($import->duplicateCount > 0) {
                $msgParts[] = "{$import->duplicateCount} duplicate(s) skipped.";
            }
            if ($import->olderDateSkippedCount > 0) {
                $msgParts[] = "{$import->olderDateSkippedCount} transaction(s) skipped because their date is earlier than the configured start date.";
            }

            if ($import->importedCount === 0) {
                if ($import->duplicateCount > 0) {
                    return back()->with('warning', 'All transactions in this file have already been imported (' . $import->duplicateCount . ' duplicates skipped).');
                }
                if ($import->olderDateSkippedCount > 0) {
                    return back()->with('warning', "All {$import->olderDateSkippedCount} transactions in this file are before the start date filter. Please provide an earlier 'From Date' during import or update Settings to import them.");
                }
                return back()->with('warning', 'No valid transaction records found in the uploaded statement.');
            }

            return back()->with('success', implode(' ', $msgParts));
        } catch (\Throwable $e) {
            \Log::error('Bank statement import error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadSampleTemplate()
    {
        return Excel::download(new SampleBankStatementTemplateExport, 'Master_Bank_Statement_Sample_Template.xlsx');
    }

    private function generateRefNo($actionType)
    {
        return BankStatement::generateRefNo($actionType);
    }

    public function setAction(Request $request, $id)
    {
        $statement  = BankStatement::findOrFail($id);
        $actionType = $request->query('action_type');

        if ($statement->action_type !== $actionType) {
            $statement->action_type = $actionType;
        }

        if (empty($statement->ref_no) || $statement->isDirty('action_type')) {
            $statement->ref_no = BankStatement::generateRefNo($actionType);
        }

        if (in_array($actionType, ['OD INTEREST', 'OD'])) {
            $statement->conform_ref_no = $statement->ref_no;
        }

        if ($actionType === 'SELF TRANSACTION') {
            $statement->conform_ref_no = $statement->ref_no;
            $statement->old_action_type = 'SELF TRANSACTION';
        }

        if ($statement->isDirty()) {
            $statement->save();
        }

        if ($actionType === 'LOAN') {
            return redirect()->route('bankstatement.showLoan', $statement->id);
        }

        if (in_array($actionType, ['OD INTEREST', 'OD'])) {
            return redirect()->route('bank.statement')->with('success', "Ref No generated for OD INTEREST: {$statement->ref_no}");
        }

        if ($actionType === 'SELF TRANSACTION') {
            return redirect()->route('bank.statement')->with('success', "Ref No generated for SELF TRANSACTION: {$statement->ref_no}");
        }

        $investor = Investor::where('statement_id', $id)->first();
        return view('accounts-admin.account-statement.show', compact('statement', 'investor'));
    }

    public function statementview(BankStatement $id)
    {
        //
    }

    public function saveAction(Request $request)
    {
        $request->validate([
            'statement_id' => 'required|exists:bank_statements,id',
            'action_type'  => 'required|string',
        ]);

        $statement  = BankStatement::findOrFail($request->statement_id);
        $actionType = $request->action_type;
        $refNo      = $this->generateRefNo($actionType);
        
        $statement->action_type = $actionType;
        $statement->ref_no      = $refNo;
        if (in_array($actionType, ['OD INTEREST', 'OD'])) {
            $statement->conform_ref_no = $refNo;
        }
        $statement->save();

        return response()->json([
            'success'     => true,
            'ref_no'      => $refNo,
            'action_type' => $actionType,
        ]);
    }

    public function showReturn($id, Request $request)
    {
        $statement  = BankStatement::findOrFail($id);
        $actionType = $request->query('action_type');

        $investor  = Investor::where('ref_no', $statement->ref_no)->first();
        $investors = Investor::all();

        $nameMatch = false;
        $message   = null;

        if ($investor) {
            if (trim(strtolower($investor->name)) === trim(strtolower($statement->name))) {
                $nameMatch = true;
                $message   = "Name Match";
            } else {
                $message = "Name not match — Please upload KYC.";
            }
        } else {
            $message = "No investor found with this REF NO.";
        }

        return view('accounts-admin.account-statement.showreturn', compact(
            'statement', 'actionType', 'investor', 'investors', 'nameMatch', 'message'
        ));
    }

    public function uploadKyc(Request $request, $loanId)
    {
        $request->validate([
            'kyc_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $loan     = \App\Models\Loan::findOrFail($loanId);
        $file     = $request->file('kyc_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filepath = $file->storeAs('loan_kyc', $filename, 'public');

        \App\Models\LoanFile::create([
            'loan_id'  => $loan->id,
            'filename' => $file->getClientOriginalName(),
            'filepath' => $filepath,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'KYC file uploaded successfully.',
            'filename' => $file->getClientOriginalName(),
            'filepath' => asset('storage/' . $filepath),
        ]);
    }

    public function showReturnPage($statementId)
    {
        $statement = BankStatement::findOrFail($statementId);
        $investor  = Investor::where('ref_no', $statement->ref_no)->first();

        return view('accounts-admin.account-statement.showreturn', compact('statement', 'investor'));
    }

    // =========================================================================
    //  saveRefNo
    // =========================================================================
    public function saveRefNo(Request $request, $id)
    {
        $request->validate(['ref_no' => 'required|string|max:255']);

        $cr                  = BankStatement::findOrFail($id);
        $cr->conform_ref_no  = $request->ref_no;
        if (empty($cr->ref_no) || in_array($cr->action_type, ['OD INTEREST', 'OD', 'SELF TRANSACTION']) || \Illuminate\Support\Str::startsWith($request->ref_no, 'OD') || \Illuminate\Support\Str::startsWith($request->ref_no, 'SELF')) {
            $cr->ref_no = $request->ref_no;
        }
        $cr->is_openinvestment = true;
        $cr->save();

        // Pair statement — search BOTH ref_no AND conform_ref_no
        $dr = BankStatement::where(function ($q) use ($request, $id) {
                $q->where('ref_no', $request->ref_no)
                  ->orWhere('conform_ref_no', $request->ref_no);
            })
            ->where('id', '!=', $id)
            ->first();

        if ($dr) {
            $dr->conform_ref_no = $request->ref_no;
            $dr->save();
        }

        // Get ALL statements with this conform_ref_no
        $statements = BankStatement::where('conform_ref_no', $request->ref_no)->get();

        try {
            $loanResult       = $this->processLoan($statements);
            $investmentResult = $this->processInvestment($statements);

            // ── LOAN ─────────────────────────────────────────────────
             if ($loanResult['is_loan']) {
            if ($loanResult['balance'] <= 0) {
                foreach ($statements as $stmt) {
                    $stmt->is_hidden      = true;
                    $stmt->is_categorized = true;
                    $stmt->save();
                }
            }

            $balanceFmt  = '₹' . number_format(abs($loanResult['balance']), 2);
            $interestFmt = '₹' . number_format($loanResult['total_interest'], 2);

            $msg = $loanResult['balance'] <= 0
                ? "Loan fully settled! Total Interest: {$interestFmt}"
                : "Loan pending — Balance: {$balanceFmt} | Interest so far: {$interestFmt}";

            return response()->json([
                'success'        => true,
                'message'        => $msg,
                'cr_id'          => $cr->id,
                'dr_id'          => $dr ? $dr->id : null,
                'is_categorized' => $loanResult['balance'] <= 0,
                'type'           => 'loan',
                'loan_balance'   => $loanResult['balance'],
                'loan_interest'  => $loanResult['total_interest'],
            ]);
        }


            // ── INVESTMENT ───────────────────────────────────────────
            if ($investmentResult['is_investment']) {
                if ($investmentResult['fully_complete']) {
                    foreach ($statements as $stmt) {
                        $stmt->is_hidden      = true;
                        $stmt->is_categorized = true;
                        $stmt->save();
                    }
                    return response()->json([
                        'success'          => true,
                        'message'          => 'Investment fully settled!',
                        'cr_id'            => $cr->id,
                        'dr_id'            => $dr ? $dr->id : null,
                        'is_categorized'   => true,
                        'type'             => 'investment',
                        'interest_pending' => false,
                    ]);
                }

                if ($investmentResult['equal_amount']) {
                    foreach ($statements as $stmt) {
                        $stmt->is_hidden      = true;
                        $stmt->is_categorized = true;
                        $stmt->save();
                    }
                    if ($investmentResult['investor']) {
                        $investmentResult['investor']->interest_status = 'pending';
                        $investmentResult['investor']->save();
                    }
                    return response()->json([
                        'success'          => true,
                        'message'          => 'Investment categorised — Interest ₹' . number_format($investmentResult['total_interest'], 2) . ' is pending.',
                        'cr_id'            => $cr->id,
                        'dr_id'            => $dr ? $dr->id : null,
                        'is_categorized'   => true,
                        'type'             => 'investment',
                        'interest_pending' => true,
                        'pending_interest' => $investmentResult['total_interest'],
                    ]);
                }

                return response()->json([
                    'success'          => false,
                    'message'          => 'Investment not yet fully repaid.',
                    'cr_id'            => $cr->id,
                    'dr_id'            => $dr ? $dr->id : null,
                    'is_categorized'   => false,
                    'type'             => 'investment',
                    'interest_pending' => false,
                ]);
            }

            // ── OD INTEREST / EXCESS / EXPANSES / OTHER ──────────────
            $totalCredit = round($statements->filter(fn($s) => in_array($s->cr_dr, ['CR', 'Credit']))->sum('amount'), 2);
            $totalDebit  = round($statements->filter(fn($s) => in_array($s->cr_dr, ['DR', 'Debit']))->sum('amount'), 2);

            $hasCr       = $statements->filter(fn($s) => in_array($s->cr_dr, ['CR', 'Credit']))->count() > 0;
            $hasDr       = $statements->filter(fn($s) => in_array($s->cr_dr, ['DR', 'Debit']))->count() > 0;

            $diff       = round(abs($totalCredit - $totalDebit), 2);
            $isComplete = ($hasCr && $hasDr && $diff == 0);

            if ($isComplete) {
                foreach ($statements as $stmt) {
                    $stmt->is_hidden      = true;
                    $stmt->is_categorized = true;
                    if (empty($stmt->old_action_type)) {
                        $stmt->old_action_type = $stmt->action_type ?? (Str::startsWith($request->ref_no, 'OD') ? 'OD INTEREST' : (Str::startsWith($request->ref_no, 'SELF') ? 'SELF TRANSACTION' : null));
                    }
                    $stmt->save();
                }
            } else {
                foreach ($statements as $stmt) {
                    $stmt->is_hidden      = false;
                    $stmt->is_categorized = false;
                    $stmt->save();
                }
            }

            return response()->json([
                'success'        => true,
                'message'        => $isComplete ? 'Categorised successfully!' : 'Ref No saved. Credit - Debit balance pending.',
                'cr_id'          => $cr->id,
                'dr_id'          => $dr ? $dr->id : null,
                'is_categorized' => $isComplete,
                'type'           => 'other',
            ]);

        } catch (\Exception $e) {
            \Log::error('saveRefNo error: ' . $e->getMessage() . ' | ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'type'    => 'error',
            ], 500);
        }
    }

    // // =========================================================================
    // //  processLoan — Reducing Balance Interest
    // //
    // //  Day 0 → DR 20,00,000 (given out)
    // //  Day 1 → CR 10,00,000 → interest on 20L × 1 day   | balance → 10L
    // //  Day 2 → CR  5,00,000 → interest on 10L × 1 day   | balance →  5L
    // //  Day 3 → CR  5,00,000 → interest on  5L × 1 day   | balance →  0
    // //
    // //  Balance check: (totalDebit + totalInterest) - totalCredit
    // //  Tolerance    : ≤ ₹1.00 rounding difference is treated as fully settled
    // // =========================================================================
    // private function processLoan($statements)
    // {
    //     // ── Find loan record ──────────────────────────────────────────
    //     $loanRecord = null;
    //     $percentage = 0;

    //     foreach ($statements as $stmt) {
    //         $loan = Loan::where('statement_id', $stmt->id)->first();
    //         if ($loan) {
    //             $loanRecord = $loan;
    //             $percentage = (float) $loan->percentage;
    //             break;
    //         }
    //     }

    //     if (!$loanRecord) {
    //         return [
    //             'is_loan'        => false,
    //             'complete'       => false,
    //             'balance'        => null,
    //             'total_interest' => 0,
    //         ];
    //     }

    //     // ── Separate DR and CR ────────────────────────────────────────
    //     $drStatements = $statements
    //         ->filter(fn($s) => in_array($s->cr_dr, ['DR', 'Debit']))
    //         ->sortBy('date')
    //         ->values();

    //     $crStatements = $statements
    //         ->filter(fn($s) => in_array($s->cr_dr, ['CR', 'Credit']))
    //         ->sortBy('date')
    //         ->values();

    //     $totalDebit  = round($drStatements->sum('amount'), 2);
    //     $totalCredit = round($crStatements->sum('amount'), 2);

    //     // ── Starting date = first DR date ─────────────────────────────
    //     $prevDate       = $drStatements->isNotEmpty()
    //         ? Carbon::parse($drStatements->first()->date)
    //         : Carbon::now();

    //     $runningBalance = $totalDebit;
    //     $totalInterest  = 0.0;

    //     // ── Group same-day CRs together ───────────────────────────────
    //     // Multiple payments on same day = ONE interest period
    //     $crGroupedByDate = $crStatements
    //         ->groupBy(fn($s) => Carbon::parse($s->date)->format('Y-m-d'))
    //         ->sortKeys();

    //     foreach ($crGroupedByDate as $dateStr => $crsOnDate) {
    //         $crDate = Carbon::parse($dateStr);

    //         // Days from previous event (DR date or last CR date)
    //         $days = $prevDate->diffInDays($crDate);

    //         // Minimum 1 day interest
    //         if ($days < 1) $days = 1;

    //         // Interest on the CURRENT running balance for this period
    //         $periodInterest = ($runningBalance * $percentage / 100) * $days;
    //         $totalInterest += $periodInterest;

    //         // Sum ALL repayments on this date → reduce balance ONCE
    //         $dayRepayment   = round($crsOnDate->sum('amount'), 2);
    //         $runningBalance = round($runningBalance - $dayRepayment, 2);
    //         $prevDate       = $crDate;

    //         \Log::info(
    //             "Loan Period | date={$dateStr} | days={$days} | " .
    //             "balance_before=" . ($runningBalance + $dayRepayment) . " | " .
    //             "repayment={$dayRepayment} | interest={$periodInterest}"
    //         );
    //     }

    //     $totalInterest = round($totalInterest, 2);

    //     // ✅ FIX: Balance must include interest
    //     // (Principal + Interest) - Total Repaid
    //     $remainingBalance = round(($totalDebit + $totalInterest) - $totalCredit, 2);

    //     // ✅ FIX: ₹1.00 tolerance for floating-point / rounding differences
    //     // If someone paid ₹X.99 instead of ₹X+1.00 due to rounding, treat as settled
    //     $isSettled = $remainingBalance <= 1.00;

    //     // Persist interest
    //     $loanRecord->interest_amount = $totalInterest;
    //     $loanRecord->save();

    //     \Log::info("Loan Summary | Debit={$totalDebit} | Credit={$totalCredit} | Interest={$totalInterest} | Balance={$remainingBalance} | Settled=" . ($isSettled ? 'YES' : 'NO'));

    //     return [
    //         'is_loan'        => true,
    //         'complete'       => $isSettled,   // ✅ use this everywhere
    //         'balance'        => $remainingBalance,
    //         'total_interest' => $totalInterest,
    //     ];
    // }

    // =========================================================================
//  processLoan — Reducing Balance Interest
//
//  Day 0 → DR 20,00,000 (given out)
//  Day 1 → CR 10,00,000 → interest on 20L × 1 day   | balance → 10L
//  Day 2 → CR  5,00,000 → interest on 10L × 1 day   | balance →  5L
//  Day 3 → CR  5,00,000 → interest on  5L × 1 day   | balance →  0
//
//  Balance check: totalDebit - totalCredit
//  Exact match only — Credit must equal Debit to categorize
// =========================================================================
private function processLoan($statements)
{
    // ── Find loan record ──────────────────────────────────────────────
    $loanRecord = null;
    $percentage = 0;
 
    foreach ($statements as $stmt) {
        $loan = Loan::where('statement_id', $stmt->id)->first();
        if ($loan) {
            $loanRecord = $loan;
            $percentage = (float) $loan->percentage;
            break;
        }
    }
 
    if (!$loanRecord) {
        return [
            'is_loan'        => false,
            'complete'       => false,
            'balance'        => null,
            'total_interest' => 0,
        ];
    }
 
    // ── Separate DR and CR ────────────────────────────────────────────
    $drStatements = $statements
        ->filter(fn($s) => in_array($s->cr_dr, ['DR', 'Debit']))
        ->sortBy('date')
        ->values();
 
    $crStatements = $statements
        ->filter(fn($s) => in_array($s->cr_dr, ['CR', 'Credit']))
        ->sortBy('date')
        ->values();
 
    $totalDebit  = round($drStatements->sum('amount'), 2);
    $totalCredit = round($crStatements->sum('amount'), 2);
 
    // ── Starting date = first DR date ─────────────────────────────────
    $prevDate       = $drStatements->isNotEmpty()
        ? Carbon::parse($drStatements->first()->date)
        : Carbon::now();
 
    $runningBalance = $totalDebit;
    $totalInterest  = 0.0;
 
    // ── KEY FIX: Group same-day CRs together ─────────────────────────
    // Multiple payments on same day = ONE interest period
    // Prevents wrong multiplication when 3 CRs come on same day
    $crGroupedByDate = $crStatements
        ->groupBy(fn($s) => Carbon::parse($s->date)->format('Y-m-d'))
        ->sortKeys();   // ensure date ascending order
 
    foreach ($crGroupedByDate as $dateStr => $crsOnDate) {
        $crDate = Carbon::parse($dateStr);
 
        // Days from previous event (DR date or last CR date)
        $days = $prevDate->diffInDays($crDate);
 
        // Minimum 1 day interest (even if repaid same day as loan given)
        if ($days < 1) $days = 1;
 
        // Interest on the CURRENT running balance for this period
        $periodInterest = ($runningBalance * $percentage / 100) * $days;
        $totalInterest += $periodInterest;
 
        // Sum ALL repayments on this date → reduce balance ONCE
        $dayRepayment   = round($crsOnDate->sum('amount'), 2);
        $runningBalance = round($runningBalance - $dayRepayment, 2);
        $prevDate       = $crDate;
 
        \Log::info(
            "Loan Period | date={$dateStr} | days={$days} | " .
            "balance_before=" . ($runningBalance + $dayRepayment) . " | " .
            "repayment={$dayRepayment} | interest={$periodInterest}"
        );
    }
 
    $totalInterest    = round($totalInterest, 2);
$remainingBalance = round($totalDebit - $totalCredit, 2);
 
    // Persist interest
    $loanRecord->interest_amount = $totalInterest;
    $loanRecord->save();
 
    \Log::info("Loan Summary | Debit={$totalDebit} | Credit={$totalCredit} | Balance={$remainingBalance} | TotalInterest={$totalInterest}");
 
    return [
        'is_loan'        => true,
        'complete'       => $remainingBalance <= 0,
        'balance'        => $remainingBalance,
        'total_interest' => $totalInterest,
    ];
}
 

    // =========================================================================
    //  processInvestment
    //
    //  Case 1 — fully_complete : CR == DR + interest  → hide rows
    //  Case 2 — equal_amount   : CR == DR (no interest paid yet) → hide rows
    //                            + set investor.interest_status = 'pending'
    //  Case 3 — still pending  : CR < DR → do nothing
    // =========================================================================
    private function processInvestment($statements)
    {
        $investorRecord = null;
        $percentage     = 0;

        foreach ($statements as $stmt) {
            if (!in_array($stmt->cr_dr, ['CR', 'Credit'])) continue;

            $investor = Investor::where('statement_id', $stmt->id)->first();
            if ($investor) {
                $investorRecord = $investor;
                $percentage     = (float) $investor->percentage;
                break;
            }
        }

        if (!$investorRecord) {
            return [
                'is_investment'  => false,
                'fully_complete' => false,
                'equal_amount'   => false,
                'is_overpaid'    => false,
                'over_paid'      => 0,
                'total_interest' => 0,
                'investor'       => null,
            ];
        }

        $crStatements = $statements->filter(fn($s) => in_array($s->cr_dr, ['CR', 'Credit']))
                                   ->sortBy('date')->values();
        $drStatements = $statements->filter(fn($s) => in_array($s->cr_dr, ['DR', 'Debit']))
                                   ->sortBy('date')->values();

        $totalInvested = round($crStatements->sum('amount'), 2);
        $totalRepaid   = round($drStatements->sum('amount'), 2);

        // ── Days calculation ──────────────────────────────────────────
        $noOfDays = 1;
        if ($crStatements->count() && $drStatements->count()) {
            $crDate   = Carbon::parse($crStatements->first()->date);
            $drDate   = Carbon::parse($drStatements->last()->date);
            $diffDays = $crDate->diffInDays($drDate);
            $noOfDays = max(1, $diffDays);
        }

        $totalInterest = round(($totalInvested * $percentage / 100) * $noOfDays, 2);

        $investorRecord->interest_amount = $totalInterest;

        $expectedWithInterest = round($totalInvested + $totalInterest, 2);

        \Log::info("Investment: CR={$totalInvested}, DR={$totalRepaid}, %={$percentage}, days={$noOfDays}, interest={$totalInterest}");

        // Case 0: OVERPAID
        if ($totalRepaid > $expectedWithInterest) {
            $overPaid = round($totalRepaid - $expectedWithInterest, 2);
            $investorRecord->interest_status = 'overpaid';
            $investorRecord->save();

            return [
                'is_investment'  => true,
                'fully_complete' => false,
                'equal_amount'   => false,
                'is_overpaid'    => true,
                'over_paid'      => $overPaid,
                'total_interest' => $totalInterest,
                'investor'       => $investorRecord,
            ];
        }

        // Case 1: Fully Settled
        if ($totalRepaid >= $expectedWithInterest) {
            $investorRecord->interest_status = 'paid';
            $investorRecord->save();

            return [
                'is_investment'  => true,
                'fully_complete' => true,
                'equal_amount'   => false,
                'is_overpaid'    => false,
                'over_paid'      => 0,
                'total_interest' => $totalInterest,
                'investor'       => $investorRecord,
            ];
        }

        // Case 2: Principal returned, interest pending
        if ($totalRepaid >= $totalInvested) {
            $investorRecord->interest_status = 'pending';
            $investorRecord->save();

            return [
                'is_investment'  => true,
                'fully_complete' => false,
                'equal_amount'   => true,
                'is_overpaid'    => false,
                'over_paid'      => 0,
                'total_interest' => $totalInterest,
                'investor'       => $investorRecord,
            ];
        }

        // Case 3: Still pending
        $investorRecord->interest_status = 'none';
        $investorRecord->save();

        return [
            'is_investment'  => true,
            'fully_complete' => false,
            'equal_amount'   => false,
            'is_overpaid'    => false,
            'over_paid'      => 0,
            'total_interest' => $totalInterest,
            'investor'       => $investorRecord,
        ];
    }

    private function processExcess($statements)
    {
        $hasInvestor = false;
        $hasLoan     = false;

        foreach ($statements as $statement) {
            if (Investor::where('statement_id', $statement->id)->exists()) {
                $hasInvestor = true;
                break;
            }
            if (Loan::where('statement_id', $statement->id)->exists()) {
                $hasLoan = true;
                break;
            }
        }

        if ($hasInvestor || $hasLoan) {
            \Log::info("Not Excess - Investment or Loan detected");
            return false;
        }

        $totalCredit = 0;
        $totalDebit  = 0;

        foreach ($statements as $statement) {
            if ($statement->cr_dr === 'CR' || $statement->cr_dr === 'Credit') {
                $totalCredit += $statement->amount;
            } elseif ($statement->cr_dr === 'DR' || $statement->cr_dr === 'Debit') {
                $totalDebit += $statement->amount;
            }
        }

        $totalCredit = round($totalCredit, 2);
        $totalDebit  = round($totalDebit, 2);

        if ($totalCredit == $totalDebit) {
            return true;
        }

        return false;
    }

    public function category(Request $request)
    {
        $query = BankStatement::where('is_hidden', 1);

        if ($request->filled('old_action_type')) {
            $oldAction = $request->old_action_type;
            $query->where(function ($q) use ($oldAction) {
                $q->where('old_action_type', $oldAction)
                  ->orWhere('action_type', $oldAction);
                if (in_array(strtoupper($oldAction), ['OD INTEREST', 'OD_INTEREST', 'OD'])) {
                    $q->orWhere('conform_ref_no', 'like', 'OD%');
                }
                if (in_array(strtoupper($oldAction), ['SELF TRANSACTION', 'SELF_TRANSACTION', 'SELF'])) {
                    $q->orWhere('conform_ref_no', 'like', 'SELF%');
                }
                if (in_array(strtoupper($oldAction), ['LOAN'])) {
                    $q->orWhere('conform_ref_no', 'like', 'LOA%');
                }
            });
        }

        if ($request->filled('old_action_type') && in_array(strtoupper($request->old_action_type), ['INVESTMENT', 'OD INTEREST', 'OD_INTEREST', 'OD', 'SELF TRANSACTION', 'SELF', 'LOAN'])) {
            $targetType = $request->old_action_type;
            $repIds = BankStatement::where('is_hidden', 1)
                ->where(function ($q) use ($targetType) {
                    $q->where('old_action_type', $targetType)
                      ->orWhere('action_type', $targetType);
                    if (in_array(strtoupper($targetType), ['OD INTEREST', 'OD_INTEREST', 'OD'])) {
                        $q->orWhere('conform_ref_no', 'like', 'OD%');
                    }
                    if (in_array(strtoupper($targetType), ['SELF TRANSACTION', 'SELF_TRANSACTION', 'SELF'])) {
                        $q->orWhere('conform_ref_no', 'like', 'SELF%');
                    }
                    if (in_array(strtoupper($targetType), ['LOAN'])) {
                        $q->orWhere('conform_ref_no', 'like', 'LOA%');
                    }
                })
                ->whereNotNull('conform_ref_no')
                ->selectRaw("COALESCE(MIN(CASE WHEN cr_dr IN ('DR', 'Debit') THEN id END), MIN(CASE WHEN cr_dr IN ('CR', 'Credit') THEN id END), MIN(id)) as rep_id")
                ->groupBy('conform_ref_no')
                ->pluck('rep_id');

            $query->where(function ($q) use ($repIds) {
                $q->whereIn('id', $repIds)
                  ->orWhereNull('conform_ref_no');
            });
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('utrno')) {
            $query->where('utrno', 'like', '%' . $request->utrno . '%');
        }

        if ($request->filled('conform_ref_no')) {
            $query->where('conform_ref_no', 'like', '%' . $request->conform_ref_no . '%');
        }

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $statements = $query->orderBy('date', 'desc')->paginate(10);
        $statements->appends($request->all());

        return view('accounts-admin.account-statement.category', compact('statements'));
    }

    public function viewByRefNo($refNo)
    {
        $bankStatements = BankStatement::where('conform_ref_no', $refNo)->get();

        if ($bankStatements->isEmpty()) {
            return redirect()->route('bank.statement')->with('error', 'No data found');
        }

        $loan      = null;
        $extraData = null;
        $investor  = null;
        $category  = null;

        foreach ($bankStatements as $statement) {
            if (!empty($statement->old_action_type) || !empty($statement->action_type)) {
                $category = $statement->old_action_type ?? $statement->action_type;
                break;
            }
        }

        if (\Illuminate\Support\Str::startsWith($refNo, 'OD')) {
            $category = 'OD INTEREST';
        }

        if ($category === 'INVESTMENT') {
            $investmentStatement = $bankStatements->where('old_action_type', 'INVESTMENT')->where('cr_dr', 'CR')->first();
            if (!$investmentStatement) {
                $investmentStatement = $bankStatements->where('action_type', 'INVESTMENT')->where('cr_dr', 'CR')->first();
            }
            if (!$investmentStatement) {
                $investmentStatement = $bankStatements->where('cr_dr', 'CR')->first() ?? $bankStatements->first();
            }

            $investor = Investor::where('ref_no', $refNo)->first();
            if (!$investor && $investmentStatement) {
                $investor = Investor::where('statement_id', $investmentStatement->id)->first();
            }
            if (!$investor) {
                $investor = Investor::whereIn('statement_id', $bankStatements->pluck('id'))->first();
            }
            if (!$investor && $investmentStatement && !empty($investmentStatement->name)) {
                $compactName = strtolower(str_replace(' ', '', $investmentStatement->name));
                $investor = Investor::all()->first(function($inv) use ($compactName) {
                    return strtolower(str_replace(' ', '', $inv->name)) === $compactName;
                });
            }
        } elseif ($category === 'LOAN') {
            $loanStatement = $bankStatements->where('old_action_type', 'LOAN')->where('cr_dr', 'DR')->first();
            if (!$loanStatement) {
                $loanStatement = $bankStatements->where('action_type', 'LOAN')->where('cr_dr', 'DR')->first();
            }
            if ($loanStatement) {
                $loan = Loan::with('files')->where('statement_id', $loanStatement->id)->first();
                if (!$loan) {
                    $loan = Loan::with('files')->where('conform_ref_no', $refNo)->first();
                }
                $extraData = $loan;
            }
        } elseif ($category === 'EXCESS') {
            $excessStatement = $bankStatements->filter(function ($statement) {
                return $statement->old_action_type === 'EXCESS' || $statement->action_type === 'EXCESS';
            })->first();
            if ($excessStatement) {
                $extraData = Excess::with('files')->where('statement_id', $excessStatement->id)->first();
                if (!$extraData) {
                    $extraData = Excess::with('files')->where('conform_ref_no', $refNo)->first();
                }
            }
        } elseif ($category === 'EXPANSES') {
            $expansesStatement = $bankStatements->filter(function ($statement) {
                return $statement->old_action_type === 'EXPANSES' || $statement->action_type === 'EXPANSES';
            })->first();
            if ($expansesStatement) {
                $extraData = Expanses::where('statement_id', $expansesStatement->id)->first();
            }
        }

        return view('bankstatement.view', compact('bankStatements', 'loan', 'extraData', 'investor', 'category'));
    }

    public function toggleUncategorized($conformRefNo)
    {
        $records = BankStatement::where('conform_ref_no', $conformRefNo)
                                ->where('is_hidden', 1)
                                ->get();

        foreach ($records as $record) {
            $record->is_hidden = 0;
            $record->save();
        }

        return redirect()->back()->with('success', 'Records updated successfully.');
    }

    public function categorizeOpenInvestment($conformRefNo)
    {
        $records = BankStatement::where('conform_ref_no', $conformRefNo)->get();

        if ($records->isEmpty()) {
            return redirect()->back()->with('error', 'No statements found for ' . $conformRefNo);
        }

        foreach ($records as $record) {
            $record->is_hidden       = 1;
            $record->is_categorized  = 1;
            if (empty($record->old_action_type)) {
                $record->old_action_type = 'INVESTMENT';
            }
            if (empty($record->action_type)) {
                $record->action_type = 'INVESTMENT';
            }
            $record->save();
        }

        // Calculate interest and update Investor record
        $crStatements = $records->filter(fn($s) => in_array($s->cr_dr, ['CR', 'Credit']));
        $drStatements = $records->filter(fn($s) => in_array($s->cr_dr, ['DR', 'Debit']));
        $totalCredit  = round($crStatements->sum('amount'), 2);
        $totalDebit   = round($drStatements->sum('amount'), 2);

        $crDates = $crStatements->pluck('date');
        $drDates = $drStatements->pluck('date');
        $noOfDays = 0;
        if ($crDates->count() && $drDates->count()) {
            $from = Carbon::parse($crDates->min());
            $to   = Carbon::parse($drDates->max());
            $noOfDays = max(1, $from->diffInDays($to));
        }

        // Flexible investor lookup
        $investor = Investor::where('ref_no', $conformRefNo)->first();
        if (!$investor) {
            $investor = Investor::whereIn('statement_id', $records->pluck('id'))->first();
        }
        if (!$investor) {
            $names = $crStatements->pluck('name')->filter()->unique();
            foreach ($names as $rawName) {
                $compactName = strtolower(str_replace(' ', '', $rawName));
                $investor = Investor::all()->first(function($inv) use ($compactName) {
                    return strtolower(str_replace(' ', '', $inv->name)) === $compactName;
                });
                if ($investor) break;
            }
        }

        $percentage    = (float) ($investor->percentage ?? 0);
        $totalInterest = round(($totalCredit * $percentage / 100) * $noOfDays, 2);

        $firstCr = $crStatements->first() ?? $records->first();

        if (!$investor) {
            Investor::create([
                'name'                => $firstCr ? trim(preg_replace('/\s+/', ' ', $firstCr->name ?? '')) : 'Unknown Investor',
                'ref_no'              => $conformRefNo,
                'statement_id'        => $firstCr ? $firstCr->id : null,
                'percentage'          => $percentage,
                'interest_amount'     => $totalInterest,
                'interest_status'     => 'pending',
                'investment_duration' => '1 day',
                'account_number'      => 'N/A',
                'ifsc_code'           => 'N/A',
            ]);
        } else {
            if ($investor->ref_no !== $conformRefNo && !Investor::where('ref_no', $conformRefNo)->exists()) {
                Investor::create([
                    'name'                => $investor->name,
                    'ref_no'              => $conformRefNo,
                    'statement_id'        => $firstCr ? $firstCr->id : null,
                    'percentage'          => $investor->percentage,
                    'interest_amount'     => $totalInterest,
                    'interest_status'     => 'pending',
                    'investment_duration' => $investor->investment_duration ?? '1 day',
                    'account_number'      => $investor->account_number ?? 'N/A',
                    'ifsc_code'           => $investor->ifsc_code ?? 'N/A',
                ]);
            } else {
                $investor->interest_amount = $totalInterest;
                $investor->interest_status = 'pending';
                $investor->save();
            }
        }

        return redirect()->to(url('/categorized?old_action_type=INVESTMENT'))
            ->with('success', "Investment {$conformRefNo} has been moved to Categorized.");
    }

    public function uncategorizeOpenInvestment($conformRefNo)
    {
        $records = BankStatement::where('conform_ref_no', $conformRefNo)->get();

        if ($records->isEmpty()) {
            return redirect()->route('bank.statement')->with('error', 'No statements found for ' . $conformRefNo);
        }

        foreach ($records as $record) {
            $record->conform_ref_no    = null;
            $record->ref_no            = null;
            $record->investment_ref    = null;
            $record->is_hidden         = 0;
            $record->is_categorized    = 0;
            $record->is_openinvestment = 0;
            $record->action_type       = null;
            $record->old_action_type   = null;
            $record->save();
        }

        Investor::where('ref_no', $conformRefNo)->delete();
        \App\Models\InvestorInterest::where('ref_no', $conformRefNo)->delete();

        return redirect()->route('bank.statement')
            ->with('success', "Investment {$conformRefNo} has been unCategorized and moved to Uncategorized Bank Statements.");
    }

    public function uncategorizedCreditReport(Request $request)
    {
        $query = BankStatement::query();

        $query->where('cr_dr', 'CR')->where('is_hidden', false);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('bank_name')) {
            $query->where('bank_name', $request->bank_name);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $statements = $query->orderBy('date', 'desc')->paginate(10);
        $statements->appends($request->all());

        return view('accounts-admin.reports.uncategorized_credit_report', compact('statements'));
    }

    public function uncategorizedDebitReport(Request $request)
    {
        $query = BankStatement::query();

        $query->where('cr_dr', 'DR');

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('bank_name')) {
            $query->where('bank_name', $request->bank_name);
        }

        $statements = $query->where('is_hidden', false)->orderBy('date', 'desc')->paginate(10);
        $statements->appends($request->all());

        return view('accounts-admin.reports.uncategorized_debit_report', compact('statements'));
    }

    public function exportUncategorizedCreditExcel(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $filename = "Credit_Report_{$request->from_date}_to_{$request->to_date}.xlsx";
        return Excel::download(new UncategorizedCreditExport($request->from_date, $request->to_date), $filename);
    }

    public function exportUncategorizedDebitExcel(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $filename = "Debit_Report_{$request->from_date}_to_{$request->to_date}.xlsx";
        return Excel::download(new UncategorizedDebitExport($request->from_date, $request->to_date), $filename);
    }

    public function categorizedCreditReport(Request $request, $category)
    {
        $query = BankStatement::query()->where('cr_dr', 'CR');

        switch (strtolower($category)) {
            case 'investment': $query->where('conform_ref_no', 'like', 'INV%'); break;
            case 'loan':       $query->where('conform_ref_no', 'like', 'LOA%'); break;
            case 'excess':     $query->where('conform_ref_no', 'like', 'UW%');  break;
            case 'odinterest':
            case 'od_interest':
            case 'od interest':
                               $query->where('conform_ref_no', 'like', 'OD%');  break;
            case 'expanse':    $query->where('conform_ref_no', 'like', 'EXP%'); break;
            case 'selftransaction':
            case 'self_transaction':
            case 'self transaction':
            case 'self':
                               $query->where('conform_ref_no', 'like', 'SELF%'); break;
            default:           abort(404);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $statements = $query->where('is_hidden', true)->orderBy('date', 'desc')->paginate(10);
        $statements->appends($request->all());

        return view('accounts-admin.reports.report', compact('statements', 'category'));
    }

    public function exportCreditReport(Request $request, $category)
    {
        $fileName = $category . '_credit_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new CreditReportExport($request->from_date, $request->to_date, $category), $fileName);
    }

    public function categorizedDebitReport(Request $request, $category)
    {
        $query = BankStatement::query()->where('cr_dr', 'DR');

        switch (strtolower($category)) {
            case 'investment': $query->where('conform_ref_no', 'like', 'INV%'); break;
            case 'loan':       $query->where('conform_ref_no', 'like', 'LOA%'); break;
            case 'excess':     $query->where('conform_ref_no', 'like', 'UW%');  break;
            case 'odinterest':
            case 'od_interest':
            case 'od interest':
                               $query->where('conform_ref_no', 'like', 'OD%');  break;
            case 'expanse':    $query->where('conform_ref_no', 'like', 'EXP%'); break;
            case 'selftransaction':
            case 'self_transaction':
            case 'self transaction':
            case 'self':
                               $query->where('conform_ref_no', 'like', 'SELF%'); break;
            default:           abort(404, 'Invalid category');
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $statements = $query->where('is_hidden', true)->orderBy('date', 'desc')->paginate(10);
        $statements->appends($request->all());

        return view('accounts-admin.reports.categorized_debit_repot', compact('statements', 'category'));
    }

    public function exportDebitReport(Request $request, $category)
    {
        $fileName = $category . '_debit_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new DebitReportExport($request->from_date, $request->to_date, $category), $fileName);
    }

   public function categorizedBalanceReport(Request $request, $category)
{
    $base = BankStatement::query();

    switch (strtolower($category)) {
        case 'investment': $base->where('conform_ref_no', 'like', 'INV%'); break;
        case 'loan':       $base->where('conform_ref_no', 'like', 'LOA%'); break;
        case 'excess':     $base->where('conform_ref_no', 'like', 'UW%');  break;
        case 'odinterest':
        case 'od_interest':
        case 'od interest':
                           $base->where('conform_ref_no', 'like', 'OD%');  break;
        case 'expanse':    $base->where('conform_ref_no', 'like', 'EXP%'); break;
        case 'selftransaction':
        case 'self_transaction':
        case 'self transaction':
        case 'self':
                           $base->where('conform_ref_no', 'like', 'SELF%'); break;
        default:           abort(404);
    }

    $base->where('is_hidden', true);

    if ($request->filled('from_date'))  $base->whereDate('date', '>=', $request->from_date);
    if ($request->filled('to_date'))    $base->whereDate('date', '<=', $request->to_date);
    if ($request->filled('ref_no'))     $base->where('conform_ref_no', 'like', '%' . $request->ref_no . '%');
    if ($request->filled('name'))       $base->where('name', 'like', '%' . $request->name . '%');
    if ($request->filled('cr_dr'))      $base->where('cr_dr', $request->cr_dr);

    if ($category === 'investment') {
        if ($request->filled('investor_name')) {
            $investorName = trim($request->investor_name);
            $statementIds = Investor::where('name', 'like', '%' . $investorName . '%')->pluck('statement_id');
            $refNos = BankStatement::whereIn('id', $statementIds)->pluck('conform_ref_no')->unique();
            $base->whereIn('conform_ref_no', $refNos);
        }
        if ($request->filled('percentage')) {
            $statementIds = Investor::where('percentage', $request->percentage)->pluck('statement_id');
            $refNos = BankStatement::whereIn('id', $statementIds)->pluck('conform_ref_no')->unique();
            $base->whereIn('conform_ref_no', $refNos);
        }
    }

    if ($category === 'loan') {
        if ($request->filled('staff_name')) {
            $staffName = trim($request->staff_name);
            $loans = Loan::where('staff_name', 'like', '%' . $staffName . '%')
                ->orWhereHas('staff', function ($q) use ($staffName) {
                    $q->where('staff_name', 'like', '%' . $staffName . '%');
                })
                ->get();
            $refNos = $loans->pluck('ref_no')->filter();
            $statementIds = $loans->pluck('statement_id')->filter();
            if ($statementIds->isNotEmpty()) {
                $stmtRefNos = BankStatement::whereIn('id', $statementIds)->pluck('conform_ref_no')->filter();
                $refNos = $refNos->merge($stmtRefNos);
            }
            $base->whereIn('conform_ref_no', $refNos->unique());
        }
        if ($request->filled('bank_name')) {
            $bankName = trim($request->bank_name);
            $loans = Loan::where('bank_name', 'like', '%' . $bankName . '%')->get();
            $refNos = $loans->pluck('ref_no')->filter();
            $statementIds = $loans->pluck('statement_id')->filter();
            if ($statementIds->isNotEmpty()) {
                $stmtRefNos = BankStatement::whereIn('id', $statementIds)->pluck('conform_ref_no')->filter();
                $refNos = $refNos->merge($stmtRefNos);
            }
            $base->whereIn('conform_ref_no', $refNos->unique());
        }
        if ($request->filled('percentage')) {
            $percentage = $request->percentage;
            $loans = Loan::where('percentage', $percentage)->get();
            $refNos = $loans->pluck('ref_no')->filter();
            $statementIds = $loans->pluck('statement_id')->filter();
            if ($statementIds->isNotEmpty()) {
                $stmtRefNos = BankStatement::whereIn('id', $statementIds)->pluck('conform_ref_no')->filter();
                $refNos = $refNos->merge($stmtRefNos);
            }
            $base->whereIn('conform_ref_no', $refNos->unique());
        }
    }

    if ($request->filled('utr_no')) {
        $refNos = BankStatement::where('utrno', 'like', '%' . $request->utr_no . '%')->pluck('conform_ref_no')->unique();
        $base->whereIn('conform_ref_no', $refNos);
    }

    $records = $base->orderBy('conform_ref_no')->orderBy('date')->get();
    $grouped = $records->groupBy('conform_ref_no');

    // ✅ Initialize ALL totals before the loop
    $reportData            = [];
    $totalCredit           = 0;
    $totalDebit            = 0;
    $totalProfit           = 0;
    $totalInterest         = 0;
    $totalReceivedInterest = 0; // ✅ FIX: was missing

    foreach ($grouped as $refNo => $items) {
        $credit = $items->where('cr_dr', 'CR')->sum('amount');
        $debit  = $items->where('cr_dr', 'DR')->sum('amount');

        $investorName       = $bankName = $staffName = $customerName = null;
        $percentage         = $interest = 0;
        $noOfDays           = 0;
        $receivedCommission = $shortExcess = $receivedInterest = 0;

        $creditTransactions = $items->where('cr_dr', 'CR');
        $debitTransactions  = $items->where('cr_dr', 'DR');

        if ($category === 'investment') {
            $investmentStatement = $items->where('old_action_type', 'INVESTMENT')->where('cr_dr', 'CR')->first();
            if ($investmentStatement) {
                $investor = Investor::where('statement_id', $investmentStatement->id)->first();
                if ($investor) {
                    $investorName = $investor->name;
                    $percentage   = $investor->percentage;
                }
            }
            $creditDates = $items->where('cr_dr', 'CR')->pluck('date');
            $debitDates  = $items->where('cr_dr', 'DR')->pluck('date');
            if ($creditDates->count() && $debitDates->count()) {
                $from     = Carbon::parse($creditDates->min());
                $to       = Carbon::parse($debitDates->max());
                $noOfDays = $from->diffInDays($to) + 1;
            }
            $interest         = ($credit * $percentage / 100) * $noOfDays;
            $shortExcess      = $credit + $interest - $debit;
            $profit           = $shortExcess;
            $receivedInterest = $debit - $credit; // ✅ investment: debit - credit

        } elseif ($category === 'loan') {
            $loan = Loan::with('staff')->whereIn('statement_id', $items->pluck('id'))->first();
            if (!$loan) {
                $loan = Loan::with('staff')->where('ref_no', $refNo)->latest('id')->first();
            }
            if ($loan) {
                $bankName     = $loan->bank_name;
                $staffName    = $loan->staff->staff_name ?? $loan->staff_name;
                $customerName = $loan->customer_name;
                $percentage   = $loan->percentage;
            }
            $creditDates = $items->where('cr_dr', 'CR')->pluck('date');
            $debitDates  = $items->where('cr_dr', 'DR')->pluck('date');
            if ($creditDates->count() && $debitDates->count()) {
                $from     = Carbon::parse($creditDates->min());
                $to       = Carbon::parse($debitDates->max());
                $noOfDays = $from->diffInDays($to) + 1;
            }
            $interest           = ($credit * $percentage / 100) * $noOfDays;
            $receivedCommission = $credit - $debit;
            $shortExcess        = $receivedCommission - $interest;
            $profit             = $shortExcess;
            $receivedInterest   = $credit - $debit; // ✅ loan: credit - debit

        } else {
            $profit           = $credit - $debit;
            $receivedInterest = 0;
        }

        $reportData[] = [
            'ref_no'              => $refNo,
            'investor_name'       => $investorName,
            'bank_name'           => $bankName,
            'staff_name'          => $staffName,
            'customer_name'       => $customerName,
            'percentage'          => $percentage,
            'no_of_days'          => $noOfDays,
            'interest'            => $interest,
            'received_commission' => $receivedCommission,
            'short_excess'        => $shortExcess,
            'received_interest'   => $receivedInterest,
            'credit'              => $credit,
            'debit'               => $debit,
            'profit'              => $profit,
            'details'             => $items,
            'credit_transactions' => $creditTransactions,
            'debit_transactions'  => $debitTransactions,
        ];

        $totalCredit           += $credit;
        $totalDebit            += $debit;
        $totalProfit           += $profit;
        $totalInterest         += $interest;
        $totalReceivedInterest += $receivedInterest; // ✅
    }

    // Filter $reportData in PHP to ensure strict consistency with filters
    if ($category === 'loan') {
        if ($request->filled('staff_name')) {
            $searchStaff = strtolower(trim($request->staff_name));
            $reportData = array_filter($reportData, function ($item) use ($searchStaff) {
                $itemStaff = strtolower(trim($item['staff_name'] ?? ''));
                return str_contains($itemStaff, $searchStaff);
            });
        }
        if ($request->filled('bank_name')) {
            $searchBank = strtolower(trim($request->bank_name));
            $reportData = array_filter($reportData, function ($item) use ($searchBank) {
                $itemBank = strtolower(trim($item['bank_name'] ?? ''));
                return str_contains($itemBank, $searchBank);
            });
        }
        if ($request->filled('percentage')) {
            $reportData = array_filter($reportData, function ($item) use ($request) {
                return (float)($item['percentage'] ?? 0) === (float)$request->percentage;
            });
        }

        // Recalculate totals after filtering
        $totalCredit           = 0;
        $totalDebit            = 0;
        $totalProfit           = 0;
        $totalInterest         = 0;
        $totalReceivedInterest = 0;

        foreach ($reportData as $item) {
            $totalCredit           += $item['credit'];
            $totalDebit            += $item['debit'];
            $totalProfit           += $item['profit'];
            $totalInterest         += $item['interest'];
            $totalReceivedInterest += $item['received_interest'];
        }
    }

    if ($request->export === 'excel') {
        return Excel::download(
            new CategoryProfitExport($reportData, $category, $totalCredit, $totalDebit, $totalProfit),
            ucfirst($category) . '_Profit_Report.xlsx'
        );
    }
   // Paginate reportData (10 records per page)
    $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
    $perPage = 10;
    $collection = collect($reportData);
    $currentPageItems = $collection->forPage($currentPage, $perPage)->values();

    $reportData = new \Illuminate\Pagination\LengthAwarePaginator(
        $currentPageItems,
        $collection->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );
    return view('accounts-admin.reports.categorized_balance_report', compact(
        'category',
        'reportData',
        'totalCredit',
        'totalDebit',
        'totalProfit',
        'totalInterest',
        'totalReceivedInterest' // ✅
    ));
}

    public function getStaffPercentage(Request $request)
    {
        $staff = BankStaff::where('staff_name', $request->staff_name)
                          ->where('bank_name', $request->bank_name)
                          ->first();

        return response()->json(['percentage' => $staff ? $staff->percentage : null]);
    }

    public function staffNameAutocomplete(Request $request)
    {
        $staffs = BankStaff::where('staff_name', 'LIKE', "%{$request->q}%")
            ->orderBy('staff_name')
            ->distinct()
            ->pluck('staff_name');

        return response()->json($staffs);
    }

    public function testInvestorFetch($refNo)
    {
        echo "<h3>Testing Investor Fetch for: $refNo</h3>";

        $investor1 = Investor::where('ref_no', $refNo)->first();
        echo "<p><strong>Method 1 (ref_no):</strong> ";
        echo $investor1 ? "Found - Name: {$investor1->name}, Percentage: {$investor1->percentage}%" : "NOT FOUND";
        echo "</p>";

        $statement = BankStatement::where('conform_ref_no', $refNo)->first();
        if ($statement) {
            $investor2 = Investor::where('statement_id', $statement->id)->first();
            echo "<p><strong>Method 2 (statement_id):</strong> ";
            echo $investor2 ? "Found - Name: {$investor2->name}, Percentage: {$investor2->percentage}%" : "NOT FOUND";
            echo "</p>";

            $investor3 = $statement->investor;
            echo "<p><strong>Method 3 (relationship):</strong> ";
            echo $investor3 ? "Found - Name: {$investor3->name}, Percentage: {$investor3->percentage}%" : "NOT FOUND";
            echo "</p>";
        }
    }

    public function dashboard()
{
    $categories = [
        'investment'      => 'INV%',
        'loan'            => 'LOA%',
        'excess'          => 'UW%',
        'odinterest'      => 'OD%',
        'expanse'         => 'EXP%',
        'selftransaction' => 'SELF%',
    ];

    $dashboardData     = [];
    $grandTotalCredit  = 0;
    $grandTotalDebit   = 0;
    $grandTotalProfit  = 0;
    $grandTotalBalance = 0;

    foreach ($categories as $categoryName => $prefix) {
        $records = BankStatement::where('conform_ref_no', 'like', $prefix)
            ->where('is_hidden', true)
            ->get();

        $credit  = $records->where('cr_dr', 'CR')->sum('amount');
        $debit   = $records->where('cr_dr', 'DR')->sum('amount');
        $balance = $credit - $debit;
        $profit  = 0;
        $grouped = $records->groupBy('conform_ref_no');

        // ✅ Loan specific trackers
        $loanTotalInterest = 0;
        $loanReceivedComm  = 0;

        foreach ($grouped as $refNo => $items) {
            $itemCredit = $items->where('cr_dr', 'CR')->sum('amount');
            $itemDebit  = $items->where('cr_dr', 'DR')->sum('amount');

            if ($categoryName === 'investment') {
                $investmentStatement = $items->where('old_action_type', 'INVESTMENT')
                                             ->where('cr_dr', 'CR')->first();
                $percentage = 0;
                if ($investmentStatement) {
                    $investor = Investor::where('statement_id', $investmentStatement->id)->first();
                    if ($investor) $percentage = $investor->percentage;
                }
                $creditDates = $items->where('cr_dr', 'CR')->pluck('date');
                $debitDates  = $items->where('cr_dr', 'DR')->pluck('date');
                $noOfDays = 0;
                if ($creditDates->count() && $debitDates->count()) {
                    $from     = Carbon::parse($creditDates->min());
                    $to       = Carbon::parse($debitDates->max());
                    $noOfDays = $from->diffInDays($to) + 1;
                }
                $interest    = ($itemCredit * $percentage / 100) * $noOfDays;
                $shortExcess = $itemCredit + $interest - $itemDebit;
                $profit     += $shortExcess;

            } elseif ($categoryName === 'loan') {
                $loan       = Loan::whereIn('statement_id', $items->pluck('id'))->first() ?? Loan::where('ref_no', $refNo)->latest('id')->first();
                $percentage = $loan ? $loan->percentage : 0;

                $creditDates = $items->where('cr_dr', 'CR')->pluck('date');
                $debitDates  = $items->where('cr_dr', 'DR')->pluck('date');
                $noOfDays = 0;
                if ($creditDates->count() && $debitDates->count()) {
                    $from     = Carbon::parse($creditDates->min());
                    $to       = Carbon::parse($debitDates->max());
                    $noOfDays = $from->diffInDays($to) + 1;
                }
                $interest           = ($itemCredit * $percentage / 100) * $noOfDays;
                $receivedCommission = $itemCredit - $itemDebit;
                $shortExcess        = $receivedCommission - $interest;
                $profit            += $shortExcess;

                // ✅ Track loan totals
                $loanTotalInterest += $interest;
                $loanReceivedComm  += $receivedCommission;

            } else {
                $profit += ($itemCredit - $itemDebit);
            }
        }

        $displayName = match($categoryName) {
            'investment'      => 'Investment',
            'loan'            => 'Loan',
            'excess'          => 'Excess',
            'odinterest'      => 'OD Interest',
            'expanse'         => 'Expanse',
            'selftransaction' => 'Self Transaction',
            default           => ucfirst($categoryName),
        };

        $dashboardData[] = [
            'category'          => $displayName,
            'category_slug'     => $categoryName,
            'credit'            => $credit,
            'debit'             => $debit,
            'profit'            => $profit,
            'balance'           => $balance,
            'total_records'     => $grouped->count(),
            'loan_interest'     => $loanTotalInterest,  // ✅
            'loan_received'     => $loanReceivedComm,   // ✅
        ];

        $grandTotalCredit  += $credit;
        $grandTotalDebit   += $debit;
        $grandTotalProfit  += $profit;
        $grandTotalBalance += $balance;
    }

    $pendingCount     = Investor::where('interest_status', 'pending')->count();
    $pendingLoanCount = \App\Models\Loan::where('interest_status', 'pending')->count();

    return view('accounts-admin.cr-dashboard', compact(
        'dashboardData', 'grandTotalCredit', 'grandTotalDebit',
        'grandTotalProfit', 'grandTotalBalance', 'pendingCount', 'pendingLoanCount'
    ));
}

    public function approveAll()
    {
        BankStatement::where('status', 'pending')->update(['status' => 'approved']);
        return back()->with('success', 'All pending statements approved successfully');
    }

    public function rejectAll()
    {
        BankStatement::where('status', 'pending')->update(['status' => 'rejected']);
        return back()->with('success', 'All pending statements rejected successfully');
    }

    public function unmatchedLoans(Request $request)
    {
        $tab     = $request->get('tab', 'unmatched');
        $grouped = BankStatement::whereNotNull('conform_ref_no')
            ->where('is_hidden', true)
            ->where('conform_ref_no', 'like', 'LOA%')
            ->get()
            ->groupBy('conform_ref_no');

        $unmatched = [];
        $cleared   = [];

        foreach ($grouped as $refNo => $statements) {
            $credits = $statements->where('cr_dr', 'CR');
            $debits  = $statements->where('cr_dr', 'DR');

            if ($credits->count() > 0 && $debits->count() > 0) {
                foreach ($credits as $cr) {
                    foreach ($debits as $dr) {
                        if (trim(strtolower($cr->name)) !== trim(strtolower($dr->name))) {
                            if (!isset($unmatched[$refNo]) && !isset($cleared[$refNo])) {
                                $loan  = \App\Models\Loan::where('ref_no', $refNo)->first();
                                $entry = ['ref_no' => $refNo, 'credit' => $cr, 'debit' => $dr, 'loan' => $loan];
                                if ($loan && $loan->mismatch_cleared) {
                                    $cleared[$refNo] = $entry;
                                } else {
                                    $unmatched[$refNo] = $entry;
                                }
                            }
                        }
                    }
                }
            }
        }

        $page    = $request->get('page', 1);
        $perPage = 10;

        $activeCollection = $tab === 'cleared'
            ? collect(array_values($cleared))
            : collect(array_values($unmatched));

        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $activeCollection->forPage($page, $perPage),
            $activeCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('bankstatement.unmatched-loans', [
            'unmatched'      => $paginated,
            'tab'            => $tab,
            'unmatchedCount' => count($unmatched),
            'clearedCount'   => count($cleared),
        ]);
    }

    public function markMismatchCleared(Request $request, $refNo)
    {
        $loan = \App\Models\Loan::where('ref_no', $refNo)->first();

        if (!$loan) {
            return response()->json(['success' => false, 'message' => 'Loan record not found.'], 404);
        }

        \DB::table('loans')->where('id', $loan->id)->update([
            'mismatch_cleared'    => 1,
            'mismatch_cleared_at' => now(),
            'mismatch_cleared_by' => auth()->id(),
            'updated_at'          => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Marked as Mismatch Cleared successfully.']);
    }

    public function getKycFiles($loanId)
    {
        $loan  = \App\Models\Loan::with('files')->findOrFail($loanId);
        $files = $loan->files->map(fn($f) => [
            'id'       => $f->id,
            'filename' => $f->filename,
            'url'      => asset('storage/' . $f->filepath),
        ]);

        return response()->json(['success' => true, 'files' => $files]);
    }

    public function utrSearch(Request $request)
    {
        $utrNo       = $request->input('utr_no');
        $utrRecord   = null;
        $relatedData = collect();
        $reportData  = [];

        $totalCredit   = 0;
        $totalDebit    = 0;
        $totalInterest = 0;
        $totalProfit   = 0;

        if ($utrNo) {
            $utrRecord = BankStatement::where('utrno', 'like', '%' . $utrNo . '%')->first();

            if ($utrRecord) {
                $conformRefNo = $utrRecord->conform_ref_no;

                $relatedRecords = BankStatement::where('conform_ref_no', $conformRefNo)
                    ->where('is_hidden', true)
                    ->orderBy('date')
                    ->get();

                $category = 'other';
                if (Str::startsWith($conformRefNo, 'INV'))      $category = 'investment';
                elseif (Str::startsWith($conformRefNo, 'LOA')) $category = 'loan';
                elseif (Str::startsWith($conformRefNo, 'UW'))  $category = 'excess';
                elseif (Str::startsWith($conformRefNo, 'EXP')) $category = 'expanse';

                $credit = $relatedRecords->where('cr_dr', 'CR')->sum('amount');
                $debit  = $relatedRecords->where('cr_dr', 'DR')->sum('amount');

                $investorName = $bankName = $staffName = $customerName = null;
                $percentage   = $interest = $noOfDays = 0;
                $receivedCommission = $shortExcess = $profit = 0;

                if ($category === 'investment') {
                    $investmentStatement = $relatedRecords->where('old_action_type', 'INVESTMENT')->where('cr_dr', 'CR')->first();
                    if ($investmentStatement) {
                        $investor = Investor::where('statement_id', $investmentStatement->id)->first();
                        if ($investor) {
                            $investorName = $investor->name;
                            $percentage   = $investor->percentage;
                        }
                    }
                    $creditDates = $relatedRecords->where('cr_dr', 'CR')->pluck('date');
                    $debitDates  = $relatedRecords->where('cr_dr', 'DR')->pluck('date');
                    if ($creditDates->count() && $debitDates->count()) {
                        $from     = Carbon::parse($creditDates->min());
                        $to       = Carbon::parse($debitDates->max());
                        $noOfDays = $from->diffInDays($to) + 1;
                    }
                    $interest    = ($credit * $percentage / 100) * $noOfDays;
                    $shortExcess = $credit + $interest - $debit;
                    $profit      = $shortExcess;

                } elseif ($category === 'loan') {
                    $loan = Loan::with('staff')->whereIn('statement_id', $relatedRecords->pluck('id'))->first() ?? Loan::with('staff')->where('ref_no', $conformRefNo)->latest('id')->first();
                    if ($loan) {
                        $bankName     = $loan->bank_name;
                        $staffName    = $loan->staff->staff_name ?? $loan->staff_name;
                        $customerName = $loan->customer_name;
                        $percentage   = $loan->percentage;
                    }
                    $creditDates = $relatedRecords->where('cr_dr', 'CR')->pluck('date');
                    $debitDates  = $relatedRecords->where('cr_dr', 'DR')->pluck('date');
                    if ($creditDates->count() && $debitDates->count()) {
                        $from     = Carbon::parse($creditDates->min());
                        $to       = Carbon::parse($debitDates->max());
                        $noOfDays = $from->diffInDays($to) + 1;
                    }
                    $interest           = ($credit * $percentage / 100) * $noOfDays;
                    $receivedCommission = $credit - $debit;
                    $shortExcess        = $receivedCommission - $interest;
                    $profit             = $shortExcess;

                } else {
                    $profit = $credit - $debit;
                }

                $reportData = [
                    'ref_no'              => $conformRefNo,
                    'category'            => $category,
                    'investor_name'       => $investorName,
                    'bank_name'           => $bankName,
                    'staff_name'          => $staffName,
                    'customer_name'       => $customerName,
                    'percentage'          => $percentage,
                    'no_of_days'          => $noOfDays,
                    'interest'            => $interest,
                    'received_commission' => $receivedCommission,
                    'short_excess'        => $shortExcess,
                    'credit'              => $credit,
                    'debit'               => $debit,
                    'profit'              => $profit,
                    'details'             => $relatedRecords,
                ];

                $totalCredit   = $credit;
                $totalDebit    = $debit;
                $totalInterest = $interest;
                $totalProfit   = $profit;
            }
        }

        if ($request->input('export') === 'excel' && !empty($reportData)) {
            return Excel::download(
                new UtrSearchExport($reportData, $utrNo),
                'UTR_' . $utrNo . '_Report.xlsx'
            );
        }

        return view('accounts-admin.reports.utr_search', compact(
            'utrNo', 'utrRecord', 'reportData',
            'totalCredit', 'totalDebit', 'totalInterest', 'totalProfit'
        ));
    }
public function loanInterestStatusList(Request $request)
{
    $loans = Loan::with(['files', 'staff'])->get();
    $loanInterestData = [];

    foreach ($loans as $loan) {
        $statement    = BankStatement::find($loan->statement_id);
        $conformRefNo = $statement?->conform_ref_no;

        $debitTotal = $creditTotal = $daysDiff = $totalInterest = $totalAmount = $balanceAmount = $simpleBalance = 0;

        if ($conformRefNo) {
            $allStatements = BankStatement::where('conform_ref_no', $conformRefNo)->get();
            $debitTotal    = $allStatements->where('cr_dr', 'DR')->sum('amount');
            $creditTotal   = $allStatements->where('cr_dr', 'CR')->sum('amount');
            $minDebitDate  = $allStatements->where('cr_dr', 'DR')->min('date');
            $maxCreditDate = $allStatements->where('cr_dr', 'CR')->max('date');

            if ($minDebitDate && $maxCreditDate) {
                $daysDiff = Carbon::parse($minDebitDate)->diffInDays(Carbon::parse($maxCreditDate)) + 1;
            }

            $interestPercent = $loan->percentage ?? 0;
            $totalInterest   = ($interestPercent / 100) * $debitTotal * $daysDiff;
            $totalAmount     = $debitTotal + $totalInterest;
            $balanceAmount   = $creditTotal - $totalAmount;
            $simpleBalance   = $creditTotal - $debitTotal;
        }

        $loanInterestData[] = [
            'loan'             => $loan,
            'conform_ref_no'   => $conformRefNo,
            'debit_total'      => $debitTotal,
            'credit_total'     => $creditTotal,
            'days_diff'        => $daysDiff,
            'interest_percent' => $loan->percentage ?? 0,
            'total_interest'   => $totalInterest,
            'total_amount'     => $totalAmount,
            'balance_amount'   => $balanceAmount,
            'simple_balance'   => $simpleBalance,
            'status'           => $loan->interest_status ?? 'pending',
            'utrno'            => $statement?->utrno,
            'name'             => $statement?->name,
            'date'             => $statement?->date,
        ];
    }

    // ✅ FIX: Convert array to collection HERE, before filtering
    $collection = collect($loanInterestData);

    if ($request->filled('conform_ref_no')) {
        $collection = $collection->filter(fn($row) =>
            str_contains(strtolower($row['conform_ref_no'] ?? ''), strtolower($request->conform_ref_no))
        );
    }

    if ($request->filled('utrno')) {
        $collection = $collection->filter(fn($row) =>
            str_contains(strtolower($row['utrno'] ?? ''), strtolower($request->utrno))
        );
    }

    if ($request->filled('name')) {
        $collection = $collection->filter(fn($row) =>
            str_contains(strtolower($row['name'] ?? ''), strtolower($request->name))
        );
    }

    if ($request->filled('staff_name')) {
        $collection = $collection->filter(fn($row) =>
            str_contains(
                strtolower($row['loan']->staff->staff_name ?? $row['loan']->staff_name ?? ''),
                strtolower($request->staff_name)
            )
        );
    }

    if ($request->filled('customer_name')) {
        $collection = $collection->filter(fn($row) =>
            str_contains(
                strtolower($row['loan']->customer_name ?? ''),
                strtolower($request->customer_name)
            )
        );
    }

    if ($request->filled('from_date')) {
        $collection = $collection->filter(fn($row) =>
            $row['date'] && Carbon::parse($row['date'])->gte(Carbon::parse($request->from_date))
        );
    }

    if ($request->filled('to_date')) {
        $collection = $collection->filter(fn($row) =>
            $row['date'] && Carbon::parse($row['date'])->lte(Carbon::parse($request->to_date))
        );
    }

    $loanInterestData = $collection->values()->toArray();

    return view('bankstatement.loan_interest_status_list', compact('loanInterestData'));
}
    public function loanInterestStatus($loanId)
    {
        $loan         = Loan::with('files')->findOrFail($loanId);
        $statement    = BankStatement::find($loan->statement_id);
        $conformRefNo = $statement?->conform_ref_no;

        $allStatements   = collect();
        $interestSummary = null;
        $allLoans        = collect();

        if ($conformRefNo) {
            $allStatements = BankStatement::where('conform_ref_no', $conformRefNo)->get();
            $debitTotal    = $allStatements->where('cr_dr', 'DR')->sum('amount');
            $creditTotal   = $allStatements->where('cr_dr', 'CR')->sum('amount');
            $minDebitDate  = $allStatements->where('cr_dr', 'DR')->min('date');
            $maxCreditDate = $allStatements->where('cr_dr', 'CR')->max('date');

            $daysDiff = 0;
            if ($minDebitDate && $maxCreditDate) {
                $daysDiff = Carbon::parse($minDebitDate)->diffInDays(Carbon::parse($maxCreditDate)) + 1;
            }

            $interestPercent = $loan->percentage ?? 0;
            $totalInterest   = ($interestPercent / 100) * $debitTotal * $daysDiff;
            $totalAmount     = $debitTotal + $totalInterest;
            $balanceAmount   = $creditTotal - $totalAmount;
            $simpleBalance   = $creditTotal - $debitTotal;

            $interestSummary = [
                'debit_total'      => $debitTotal,
                'credit_total'     => $creditTotal,
                'days_diff'        => $daysDiff,
                'interest_percent' => $interestPercent,
                'total_interest'   => $totalInterest,
                'total_amount'     => $totalAmount,
                'balance_amount'   => $balanceAmount,
                'simple_balance'   => $simpleBalance,
                'conform_ref_no'   => $conformRefNo,
            ];

            $statementIds = BankStatement::where('conform_ref_no', $conformRefNo)->pluck('id');
            $allLoans     = Loan::whereIn('statement_id', $statementIds)->get();
        }

        return view('bankstatement.loan_interest_status', compact(
            'loan', 'allStatements', 'interestSummary', 'allLoans', 'conformRefNo'
        ));
    }

    public function acceptLoanInterest(Request $request, $loanId)
{
    $request->validate([
        'interest_remark' => 'required|string|max:500',
    ], [
        'interest_remark.required' => 'Please enter a reason before accepting.',
    ]);
 
    $loan = Loan::findOrFail($loanId);
 
    if ($loan->interest_status === 'accepted') {
        return redirect()->back()->with('error', 'Interest is already accepted.');
    }
 
    $loan->interest_status     = 'accepted';
    $loan->interest_amount     = $request->input('interest_amount', 0);
    $loan->balance_amount      = $request->input('balance_amount', 0);
    $loan->debit_amount        = $request->input('debit_amount', 0);
    $loan->credit_amount       = $request->input('credit_amount', 0);
    $loan->interest_remark     = $request->input('interest_remark');   // ✅ save reason
    $loan->interest_updated_at = now();
    $loan->save();
 
    return redirect()
        ->route('loan.interest.status', $loan->id)
        ->with('success', 'Loan interest accepted successfully.');
}
 
// ✅ 2. rejectLoanInterest — reason save பண்ணணும்
public function rejectLoanInterest(Request $request, $loanId)
{
    $request->validate([
        'interest_remark' => 'required|string|max:500',
    ], [
        'interest_remark.required' => 'Please enter a reason before rejecting.',
    ]);

    $loan = Loan::findOrFail($loanId);

    $loan->interest_status     = 'rejected';
    $loan->interest_remark     = $request->input('interest_remark');
    $loan->interest_updated_at = now();
    $loan->save();

    // ✅ conform_ref_no find பண்ணி is_hidden=1 records → is_hidden=0 மாத்து
    $statement = BankStatement::find($loan->statement_id);

    if ($statement && $statement->conform_ref_no) {
        BankStatement::where('conform_ref_no', $statement->conform_ref_no)
            ->where('is_hidden', 1)
            ->update(['is_hidden' => 0]);

        \Log::info("Loan Rejected | conform_ref_no={$statement->conform_ref_no} | is_hidden reset to 0");
    }

    return redirect()
        ->route('loan.interest.status', $loan->id)
        ->with('success', 'Loan interest rejected and hidden records restored.');
}
  
public function exportLoanInterestList(Request $request) // ✅ Request add பண்ணினோம்
{
    $loans        = Loan::with(['files', 'staff'])->get();
    $exportStatus = $request->input('export_status', 'all');
 
    // ✅ Filter பண்ணின loans மட்டும் export ஆகும்
    $loans = $loans->filter(function ($loan) use ($request) {
        // Staff name filter — bank_staff relation வழியா
        if ($request->filled('staff_name')) {
            $staffName = strtolower($loan->staff->staff_name ?? $loan->staff_name ?? '');
            if (!str_contains($staffName, strtolower($request->staff_name))) return false;
        }
        // Name filter
        if ($request->filled('name')) {
            $statement = BankStatement::find($loan->statement_id);
            if (!str_contains(strtolower($statement?->name ?? ''), strtolower($request->name))) return false;
        }
        // Ref No filter
        if ($request->filled('conform_ref_no')) {
            $statement = BankStatement::find($loan->statement_id);
            if (!str_contains(strtolower($statement?->conform_ref_no ?? ''), strtolower($request->conform_ref_no))) return false;
        }
        return true;
    });
 
    $rows        = [];
    $utrCellMap  = [];
 
    // Header row (row 1)
    $rows[] = [
        'Ref No', 'Bank Name', 'Staff Name', 'Customer Name',
        'Interest %', 'Debit Total', 'Credit Total', 'Days',
        'Interest Amt', 'Total (D+I)', 'Simple Balance', 'Balance (C-Total)',
        'Status', 'Reason', 'Updated At'
    ];
 
    $currentExcelRow = 2;
 
    foreach ($loans as $loan) {
        $statement    = BankStatement::find($loan->statement_id);
        $conformRefNo = $statement?->conform_ref_no;
 
        $debitTotal = $creditTotal = $daysDiff = $totalInterest
                    = $totalAmount = $balanceAmount = $simpleBalance = 0;
        $allStatements = collect();
 
        if ($conformRefNo) {
            $allStatements = BankStatement::where('conform_ref_no', $conformRefNo)
                                          ->orderBy('date', 'asc')
                                          ->get();
 
            $debitTotal    = $allStatements->where('cr_dr', 'DR')->sum('amount');
            $creditTotal   = $allStatements->where('cr_dr', 'CR')->sum('amount');
            $minDebitDate  = $allStatements->where('cr_dr', 'DR')->min('date');
            $maxCreditDate = $allStatements->where('cr_dr', 'CR')->max('date');
 
            if ($minDebitDate && $maxCreditDate) {
                $daysDiff = Carbon::parse($minDebitDate)
                                  ->diffInDays(Carbon::parse($maxCreditDate)) + 1;
            }
 
            $interestPercent = $loan->percentage ?? 0;
            $totalInterest   = ($interestPercent / 100) * $debitTotal * $daysDiff;
            $totalAmount     = $debitTotal + $totalInterest;
            $balanceAmount   = $creditTotal - $totalAmount;
            $simpleBalance   = $creditTotal - $debitTotal;
        }
 
        $status = $loan->interest_status ?? 'pending';
 
        // ✅ Export status filter
        if ($exportStatus === 'pending') {
            if ($status !== 'pending' || $balanceAmount >= 0) continue;
        } elseif ($exportStatus === 'accepted') {
            if ($status !== 'accepted') continue;
        } elseif ($exportStatus === 'rejected') {
            if ($status !== 'rejected') continue;
        }
        // 'all' = எதுவும் skip பண்ணாம
 
        // ✅ Staff name — relation வழியா
        $staffName = $loan->staff->staff_name ?? $loan->staff_name ?? '-';
 
        // ── Loan summary row ──────────────────────────────────────────
        $rows[] = [
            '__SUMMARY__' . ($conformRefNo ?? '-'),
            $loan->bank_name     ?? '-',
            $staffName,                              // ✅ fixed
            $loan->customer_name ?? '-',
            ($loan->percentage ?? 0) . '%',
            number_format($debitTotal,    2, '.', ''),
            number_format($creditTotal,   2, '.', ''),
            $daysDiff,
            number_format($totalInterest, 2, '.', ''),
            number_format($totalAmount,   2, '.', ''),
            number_format($simpleBalance, 2, '.', ''),
            number_format($balanceAmount, 2, '.', ''),
            strtoupper($status),
            $loan->interest_remark ?? '-',
            $loan->interest_updated_at
                ? Carbon::parse($loan->interest_updated_at)->format('d-m-Y H:i')
                : '-',
        ];
        $currentExcelRow++;
 
        // ── Statement sub-rows ────────────────────────────────────────
        if ($allStatements->isNotEmpty()) {
 
            $rows[] = [
                '__SUBHEADER__',
                'Date', 'UTR No', 'Name', 'Amount', 'CR/DR',
                '', '', '', '', '', '', '', '', ''
            ];
            $currentExcelRow++;
 
            foreach ($allStatements as $stmt) {
                $utrCellMap[$currentExcelRow] = 3;
 
                $rows[] = [
                    '__STMT__',
                    $stmt->date ? Carbon::parse($stmt->date)->format('d-m-Y') : '-',
                    (string)($stmt->utrno ?? '-'),
                    $stmt->name  ?? '-',
                    number_format($stmt->amount, 2, '.', ''),
                    $stmt->cr_dr ?? '-',
                    '', '', '', '', '', '', '', '', ''
                ];
                $currentExcelRow++;
            }
 
            $rows[] = array_fill(0, 15, '');
            $currentExcelRow++;
        }
    }
 
    $statusLabel = match($exportStatus) {
        'pending'  => 'Pending',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
        default    => 'All',
    };
 
    $filename = 'Loan_Interest_' . $statusLabel . '_' . now()->format('Y-m-d_H-i') . '.xlsx';
 
    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\LoanInterestListExport($rows, $utrCellMap),
        $filename
    );
}
    public function removeRefNo(Request $request, $id)
{
    try {
        $statement = BankStatement::findOrFail($id);

        // ✅ இந்த ஒரு record மட்டும் clear — வேற எதுவும் தொடக்கூடாது
        $statement->conform_ref_no = null;
        $statement->ref_no         = null;
        $statement->is_hidden      = false;
        $statement->is_categorized = false;
        $statement->save();

        return response()->json(['success' => true, 'message' => 'Ref No removed successfully.']);

    } catch (\Exception $e) {
        \Log::error('removeRefNo error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

    // delete records for admin approval code
    
    // ✅ STAFF: Request deletion (replaces direct delete)
public function requestDelete($id)
{
    // ✅ withTrashed() — soft deleted records கூட find ஆகும்
    $statement = BankStatement::withTrashed()->findOrFail($id);

    if ($statement->delete_status === 'pending') {
        return back()->with('warning', 'Delete request already pending admin approval.');
    }

    $statement->update([
        'delete_status'       => 'pending',
        'delete_requested_at' => now(),
        'delete_requested_by' => auth()->id(),
    ]);

    return back()->with('success', 'Delete request sent to admin for approval.');
}
// ✅ ADMIN: View all pending delete requests
// ✅ pendingDeletes — withTrashed() சேர்க்கணும்
public function pendingDeletes(Request $request)
{
    $statements = BankStatement::withTrashed()  // ← இது தான் fix
        ->where('delete_status', 'pending')
        ->orderBy('delete_requested_at', 'asc')
        ->paginate(10);

    return view('accounts-admin.account-statement.pending-deletes', compact('statements'));
}

// ✅ approveDelete — withTrashed() சேர்க்கணும்
public function approveDelete($id)
{
    $statement = BankStatement::withTrashed()
        ->where('delete_status', 'pending')
        ->findOrFail($id);

    $statement->update(['delete_status' => 'approved']);
    $statement->forceDelete(); // permanent delete

    return back()->with('success', 'Record permanently deleted.');
}

// ✅ rejectDelete — withTrashed() சேர்க்கணும்
public function rejectDelete($id)
{
    $statement = BankStatement::withTrashed()
        ->where('delete_status', 'pending')
        ->findOrFail($id);

    $statement->update([
        'delete_status'       => 'rejected',
        'delete_requested_at' => null,
        'delete_requested_by' => null,
    ]);

    return back()->with('success', 'Delete request rejected.');
}
public function deleteKyc($fileId)
{
    try {
        $file = \App\Models\LoanFile::findOrFail($fileId);
        \Storage::disk('public')->delete($file->filepath);
        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully.',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
 public function manualCategorize(Request $request, $id)
    {
        $request->validate([
            'manual_category' => 'required|in:INVESTMENT,LOAN,EXPANSES,EXCESS,OD INTEREST,SELF TRANSACTION,OTHER',
            'manual_note'     => 'nullable|string|max:500',
        ]);
 
        $statement = BankStatement::findOrFail($id);
 
        $statement->is_manual_categorized  = true;
        $statement->manual_category        = $request->manual_category;
        $statement->manual_note            = $request->manual_note;
        $statement->manual_categorized_by  = auth()->id();
        $statement->manual_categorized_at  = now();
        $statement->is_hidden              = true;   // hide from main list
        $statement->save();
 
        return response()->json([
            'success'   => true,
            'message'   => 'Transaction manually categorized as ' . $request->manual_category . '.',
            'id'        => $statement->id,
        ]);
    }
 
    // =========================================================================
    //  manualCategorizedList — View all manually categorized transactions
    //  GET /bank-statement/manual-categorized
    // =========================================================================
    public function manualCategorizedList(Request $request)
    {
        $query = BankStatement::where('is_manual_categorized', true);
 
        if ($request->filled('manual_category')) {
            $query->where('manual_category', $request->manual_category);
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        } elseif ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }
        if ($request->filled('utrno')) {
            $query->where('utrno', 'like', '%' . $request->utrno . '%');
        }
 
        $statements = $query->orderBy('manual_categorized_at', 'desc')->paginate(15);
        $statements->appends($request->all());
 
        return view('accounts-admin.account-statement.manual-categorized', compact('statements'));
    }
 
    // =========================================================================
    //  removeManualCategory — Restore a manually categorized record back
    //  POST /bank-statement/remove-manual-category/{id}
    // =========================================================================
    public function removeManualCategory($id)
    {
        $statement = BankStatement::findOrFail($id);
 
        $statement->is_manual_categorized  = false;
        $statement->manual_category        = null;
        $statement->manual_note            = null;
        $statement->manual_categorized_by  = null;
        $statement->manual_categorized_at  = null;
        $statement->is_hidden              = false;
        $statement->save();
 
        return redirect()->back()->with('success', 'Record restored to main bank statement list.');
    }
    public function openInvestments(Request $request)
{
    // Get all CR statements with INV% ref_no that are NOT hidden
    $pendingStatements = BankStatement::where('conform_ref_no', 'like', 'INV%')
        ->where('is_hidden', false)
        ->whereNotNull('conform_ref_no')
        ->get()
        ->groupBy('conform_ref_no');

    $openInvestments = [];

    foreach ($pendingStatements as $refNo => $statements) {
        // Find the investor record
        $crStatement = $statements->firstWhere('cr_dr', 'CR')
                    ?? $statements->firstWhere('cr_dr', 'Credit')
                    ?? $statements->first();

        // Flexible investor lookup across ref_no, statement IDs, name, and fallback
        $investor = Investor::where('ref_no', $refNo)->first();

        if (!$investor) {
            $stmtIds = $statements->pluck('id');
            $investor = Investor::whereIn('statement_id', $stmtIds)->first();
        }

        if (!$investor) {
            $names = $statements->whereIn('cr_dr', ['CR', 'Credit'])->pluck('name')->filter()->unique();
            if ($names->isEmpty() && $crStatement && !empty($crStatement->name)) {
                $names = collect([$crStatement->name]);
            }
            foreach ($names as $rawName) {
                $compactName = strtolower(str_replace(' ', '', $rawName));
                $investor = Investor::all()->first(function($inv) use ($compactName) {
                    return strtolower(str_replace(' ', '', $inv->name)) === $compactName;
                });
                if ($investor) break;
            }
        }

        if (!$investor) {
            $fallbackName = $crStatement ? trim(preg_replace('/\s+/', ' ', $crStatement->name ?? '')) : '';
            $investor = (object)[
                'id'         => null,
                'name'       => $fallbackName ?: 'Unknown Investor',
                'percentage' => 0,
                'remarks'    => '',
            ];
        }

        // Get ALL statements with this ref_no (including hidden ones)
        $allStatements = BankStatement::where('conform_ref_no', $refNo)
            ->orderBy('date', 'asc')
            ->get();

        $totalCredit = round($allStatements
            ->filter(fn($s) => in_array($s->cr_dr, ['CR','Credit']))
            ->sum('amount'), 2);

        $totalDebit = round($allStatements
            ->filter(fn($s) => in_array($s->cr_dr, ['DR','Debit']))
            ->sum('amount'), 2);

        // Calculate interest
        $crDates = $allStatements->filter(fn($s) => in_array($s->cr_dr, ['CR','Credit']))->pluck('date');
        $drDates = $allStatements->filter(fn($s) => in_array($s->cr_dr, ['DR','Debit']))->pluck('date');

        $noOfDays = 0;
        if ($crDates->count() && $drDates->count()) {
            $from = Carbon::parse($crDates->min());
            $to   = Carbon::parse($drDates->max());
            $noOfDays = max(1, $from->diffInDays($to));
        }

        $percentage    = (float) ($investor->percentage ?? 0);
        $totalInterest = round(($totalCredit * $percentage / 100) * $noOfDays, 2);
        $expectedTotal = round($totalCredit + $totalInterest, 2);
        $stillNeeded   = round($expectedTotal - $totalDebit, 2);

        // Auto-categorize if credit and debit are equal (principal repaid)
        if ($totalCredit > 0 && $totalDebit >= $totalCredit) {
            BankStatement::where('conform_ref_no', $refNo)->update([
                'is_hidden'       => 1,
                'is_categorized'  => 1,
                'old_action_type' => 'INVESTMENT',
                'action_type'     => 'INVESTMENT',
            ]);

            $firstCr = $allStatements->firstWhere('cr_dr', 'CR') ?? $allStatements->firstWhere('cr_dr', 'Credit') ?? $allStatements->first();
            $invRow = Investor::where('ref_no', $refNo)->first();
            if (!$invRow) {
                Investor::create([
                    'name'                => $investor->name ?? ($firstCr ? trim(preg_replace('/\s+/', ' ', $firstCr->name ?? '')) : 'Unknown Investor'),
                    'ref_no'              => $refNo,
                    'statement_id'        => $firstCr ? $firstCr->id : null,
                    'percentage'          => $percentage,
                    'interest_amount'     => $totalInterest,
                    'interest_status'     => 'pending',
                    'investment_duration' => '1 day',
                    'account_number'      => $investor->account_number ?? 'N/A',
                    'ifsc_code'           => $investor->ifsc_code ?? 'N/A',
                ]);
            } else {
                $invRow->interest_amount = $totalInterest;
                $invRow->interest_status = 'pending';
                $invRow->save();
            }

            continue; // Do not include in open investments
        }

        // Determine status
        if ($totalDebit <= 0) {
            $status = 'no_return';
        } elseif ($totalDebit >= $totalCredit && $totalDebit < $expectedTotal) {
            $status = 'interest_pending';  // principal returned, interest owed
        } else {
            $status = 'partial_return';
        }

        $openInvestments[] = [
            'ref_no'         => $refNo,
            'investor'       => $investor,
            'all_statements' => $allStatements,
            'total_credit'   => $totalCredit,
            'total_debit'    => $totalDebit,
            'no_of_days'     => $noOfDays,
            'percentage'     => $percentage,
            'total_interest' => $totalInterest,
            'expected_total' => $expectedTotal,
            'still_needed'   => $stillNeeded,
            'status'         => $status,
            'cr_date'        => $crDates->min(),
        ];
    }

    // Filters
    $collection = collect($openInvestments);

    if ($request->filled('name')) {
        $collection = $collection->filter(fn($row) =>
            str_contains(strtolower($row['investor']->name ?? ''), strtolower($request->name))
        );
    }
    if ($request->filled('ref_no')) {
        $collection = $collection->filter(fn($row) =>
            str_contains(strtolower($row['ref_no']), strtolower($request->ref_no))
        );
    }
    if ($request->filled('status')) {
        $collection = $collection->filter(fn($row) => $row['status'] === $request->status);
    }
    if ($request->filled('remarks')) {
        $collection = $collection->filter(fn($row) =>
            str_contains(strtolower($row['investor']->remarks ?? ''), strtolower($request->remarks))
        );
    }

    $openInvestments   = $collection->values()->toArray();
    $totalOpen         = count($openInvestments);
    $totalInvested     = collect($openInvestments)->sum('total_credit');
    $totalStillPending = collect($openInvestments)->sum('still_needed');

    return view('accounts-admin.investment.open-investments',
        compact('openInvestments', 'totalOpen', 'totalInvested', 'totalStillPending'));
}
}