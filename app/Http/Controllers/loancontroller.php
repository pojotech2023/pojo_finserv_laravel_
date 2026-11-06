<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\LoanFile; // if using files
use App\Models\BankStatement;
use App\Models\Account;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage; //
use Illuminate\Support\Facades\Log;

class loancontroller extends Controller
{

    
public function showLoan(Request $request, $id)
{
    $statement = \App\Models\BankStatement::findOrFail($id);
    $actionType = $request->query('action_type');

   if ($actionType) {
    if ($statement->action_type !== $actionType) {
        $statement->action_type = $actionType;
        $statement->ref_no = \App\Models\BankStatement::generateRefNo($actionType); // update when action_type changes
    } elseif (empty($statement->ref_no)) {
        $statement->ref_no = \App\Models\BankStatement::generateRefNo($actionType); // create if missing
    }

    $statement->save();
}


    // Load loan with its files
    $loan = \App\Models\Loan::with('files')->where('statement_id', $id)->first();

    return view('accounts-admin.account-statement.showloan', compact('statement', 'loan'));
}


 
public function saveLoan(Request $request, $id)
{
    // ✅ RELAXED VALIDATION - WhatsApp images-க்கு
    $request->validate([
        'bank_name'       => 'required|string',
        'staff_name'      => 'required|string',
        'customer_name'   => 'required|string',
        'percentage'      => 'required|numeric|min:0|max:100',
        'period_unit'     => 'required|string|in:day,week,month,year',
        'interest_amount' => 'nullable',
        'ref_no'          => 'nullable',
        'period_count'    => 'required|integer|min:1',

        // 🔥 KEY FIX: Use 'file' instead of strict 'mimes'
        // WhatsApp images-ல் MIME type mismatch இருக்கலாம்
        'aadhar_front' => 'nullable|file|max:5120',
        'aadhar_back'  => 'nullable|file|max:5120',
        'pan_card'     => 'nullable|file|max:5120',
        'live_photo'   => 'nullable|file|max:5120',
        'cheque_leaf'  => 'nullable|file|max:5120',
    ]);

    $interestAmount = $this->cleanAmount($request->interest_amount);

    // Check if loan already exists
    $existingLoan = Loan::where('statement_id', $id)->first();
    $staffRecord = \App\Models\BankStaff::where('staff_name', $request->staff_name)->first();



    if ($existingLoan) {
        $existingLoan->old_percentage      = $existingLoan->percentage;
        $existingLoan->old_interest_amount = $this->cleanAmount($existingLoan->interest_amount);

        $existingLoan->bank_name       = $request->bank_name;
        $existingLoan->staff_name      = $request->staff_name;
        $existingLoan->staff_id        = $staffRecord?->id;
        $existingLoan->customer_name   = $request->customer_name;
        $existingLoan->percentage      = $request->percentage;
        $existingLoan->interest_period = $request->period_unit;
        $existingLoan->interest_amount = $interestAmount;
        $existingLoan->period_count    = $request->period_count;
        $existingLoan->ref_no          = $request->ref_no;
        $existingLoan->approval_status = 'pending';
        $existingLoan->rejection_reason = null;

        // ✅ Upload with debug logging
        $existingLoan = $this->uploadLoanDocuments($request, $existingLoan);
        $existingLoan->save();
        $loan = $existingLoan;

        \Log::info("Loan edited - Old %: {$existingLoan->old_percentage}, New %: {$existingLoan->percentage}");

    } else {
        $loan = Loan::create([
            'statement_id'    => $id,
            'staff_id'   => $staffRecord?->id, 
            'bank_name'       => $request->bank_name,
            'staff_name'      => $request->staff_name,
            'customer_name'   => $request->customer_name,
            'percentage'      => $request->percentage,
            'interest_period' => $request->period_unit,
            'interest_amount' => $interestAmount,
            'period_count'    => $request->period_count,
            'ref_no'          => $request->ref_no,
            'approval_status' => 'pending',
        ]);

        $loan = $this->uploadLoanDocuments($request, $loan);
        $loan->save();
    }

    // Update bank_statements table
    $bankStatement = BankStatement::find($id);
    if ($bankStatement) {
        $bankStatement->conform_ref_no  = $request->ref_no;
        $bankStatement->old_action_type = $request->old_action_type ?: 'LOAN';
        $bankStatement->is_hidden       = 1;
        $bankStatement->is_categorized  = 1;
        $bankStatement->save();
    }

    if (!empty($request->ref_no)) {
        BankStatement::where('conform_ref_no', $request->ref_no)
            ->update([
                'is_hidden'       => 1,
                'is_categorized'  => 1,
                'old_action_type' => 'LOAN',
            ]);
    }

    // Handle KYC files (multiple)
    if ($request->hasFile('kyc_files')) {
        foreach ($request->file('kyc_files') as $file) {
            $path = $file->store('kyc_files', 'public');
            $loan->files()->create([
                'filename' => $file->getClientOriginalName(),
                'filepath' => $path,
            ]);
        }
    }

    return redirect()
        ->route('bank.statement')
        ->with('success', 'Loan details saved and pending admin approval.');
}
// =====================================================================
// Helper: Upload loan document files
// =====================================================================
private function uploadLoanDocuments(Request $request, Loan $loan): Loan
{
    $docFields = ['aadhar_front', 'aadhar_back', 'pan_card', 'live_photo', 'cheque_leaf'];

    foreach ($docFields as $field) {
        if ($request->hasFile($field)) {
            $file = $request->file($field);
            
            // 🔍 DEBUG LOGGING
            \Log::info("Processing file: {$field}");
            \Log::info("Original name: " . $file->getClientOriginalName());
            \Log::info("Extension: " . $file->getClientOriginalExtension());
            \Log::info("Mime Type: " . $file->getMimeType());
            \Log::info("Size: " . $file->getSize());
            \Log::info("Error: " . $file->getError());
            \Log::info("Valid: " . ($file->isValid() ? 'YES' : 'NO'));

            // Delete old file if exists
            if ($loan->$field && \Storage::disk('public')->exists($loan->$field)) {
                \Storage::disk('public')->delete($loan->$field);
            }

            // ✅ FIX: WhatsApp images-ல் extension-அ force செய்யுங்கள்
            $extension = $file->getClientOriginalExtension();
            
            // If no extension or invalid, detect from mime
            if (empty($extension) || !in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp', 'pdf'])) {
                $mimeToExt = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp',
                    'application/pdf' => 'pdf',
                ];
                $extension = $mimeToExt[$file->getMimeType()] ?? 'jpg';
            }

            // Generate clean filename (remove special chars like parentheses)
            $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = $cleanName . '_' . time() . '.' . $extension;
            
            // Store in specific folder
            $path = $file->storeAs('loan_documents/' . $field, $filename, 'public');
            
            \Log::info("Stored at: {$path}");
            
            $loan->$field = $path;
        }
    }

    return $loan;
}private function cleanAmount($amount)
{
    if ($amount === null) {
        return null;
    }

    // Remove ₹, commas, spaces and anything not number or dot
    return preg_replace('/[^\d.]/', '', $amount);
}

public function adminApprovalList()
{
    // Only EDITED records (has old_percentage OR old_interest_amount)
    $pendingLoans = Loan::with(['statement', 'files'])
        ->where('approval_status', 'pending')
        ->where(function($q) {
            $q->whereNotNull('old_percentage')
              ->orWhereNotNull('old_interest_amount');
        })
        ->orderBy('updated_at', 'desc')
        ->get();

    $approvedLoans = Loan::with(['statement', 'approver'])
        ->where('approval_status', 'approved')
        ->orderBy('approved_at', 'desc')
        ->limit(20)
        ->get();

    $rejectedLoans = Loan::with(['statement'])
        ->where('approval_status', 'rejected')
        ->orderBy('updated_at', 'desc')
        ->limit(20)
        ->get();

    return view('admin.loan-approvals', compact('pendingLoans', 'approvedLoans', 'rejectedLoans'));
}

public function viewLoanDetail($loanId)
{
    $loan = Loan::with(['statement', 'files', 'approver'])->findOrFail($loanId);
    
    return view('admin.loan-detail', compact('loan'));
}

public function approveLoan(Request $request, $loanId)
{
    $loan = Loan::findOrFail($loanId);
    
    $loan->approval_status = 'approved';
    $loan->approved_at = now();
   $loan->approved_by = auth()->user()->id ?? null;

    $loan->rejection_reason = null;
    
    // Clear old values after approval
    $loan->old_percentage = null;
    $loan->old_interest_amount = null;
    
    $loan->save();

    \Log::info("Loan ID {$loanId} approved by admin " . auth()->id());

    return redirect()->route('admin.loan.approvals')->with('success', 'Loan approved successfully.');
}

public function rejectLoan(Request $request, $loanId)
{
    $request->validate([
        'rejection_reason' => 'required|string|max:500',
    ]);

    $loan = Loan::findOrFail($loanId);
    
    // Restore old values
    if ($loan->old_percentage !== null) {
        \Log::info("Restoring old percentage: {$loan->old_percentage} (was {$loan->percentage})");
        $loan->percentage = $loan->old_percentage;
    }
    
    if ($loan->old_interest_amount !== null) {
        \Log::info("Restoring old interest: {$loan->old_interest_amount} (was {$loan->interest_amount})");
        $loan->interest_amount = $loan->old_interest_amount;
    }
    
    $loan->approval_status = 'rejected';
    $loan->rejection_reason = $request->rejection_reason;
    $loan->old_percentage = null;
    $loan->old_interest_amount = null;
    
    $loan->save();

    // ✅ UNHIDE ALL RELATED BANK STATEMENTS
    $conformRefNo = $loan->ref_no;
    
    if ($conformRefNo) {
        $affected = BankStatement::where('conform_ref_no', $conformRefNo)
            ->update([
                'is_hidden' => 0,
                'is_categorized' => 0,
            ]);
        
        \Log::info("Unhidden {$affected} bank statements with conform_ref_no: {$conformRefNo}");
    }

    return redirect()->route('admin.loan.approvals')->with('success', 'Loan rejected and records unhidden.');
}

public function bankName(Request $request)
{
     $search = $request->q;
    $names = Account::where('bank_name', 'LIKE', "%{$search}%")
        ->limit(10)
        ->pluck('bank_name');
    return response()->json($names);
}

    public function staffName(Request $request)
    {
        $search = $request->q;
    $names = Employee::where('name', 'LIKE', "%{$search}%")
        ->limit(10)
        ->pluck('name');
    return response()->json($names);
    }


   public function getByRefNo($refNo)
{
    $loan = Loan::with('files')->where('ref_no', $refNo)->first();

    if ($loan) {
        // Fetch the associated BankStatement
        $statement = $loan->statement;

        $nameMatch = $statement && $statement->name === $loan->customer_name;

        return response()->json([
            'success' => true,
            'loan' => $loan,
            'kyc' => $loan->files->first() ?? null,
            'nameMatch' => $nameMatch
        ]);
    } else {
        return response()->json(['success' => false]);
    }
}


public function savekyc(Request $request)
{
   
    $request->validate([
        'ref_no' => 'required',
        'loan_kyc_upload' => 'nullable|file|mimes:jpg,jpeg,png,pdf'
    ]);

    $loan = Loan::where('ref_no', $request->ref_no)->first();
    if(!$loan){
        return back()->with('error', 'Loan not found.');
    }

    if($request->hasFile('loan_kyc_upload')){
        $file = $request->file('loan_kyc_upload');
        $filename = time().'_'.$file->getClientOriginalName();
        $filepath = $file->storeAs('loan_kyc_documents', $filename, 'public');

        // Save path directly in loans table
        $loan->kyc_file = $filepath;
        $loan->save();
    }

    return back()->with('success', 'KYC uploaded successfully.');
}


public function showReturnPage($statementId)
{
    $statement = BankStatement::findOrFail($statementId);
    $loan = loan::with('files')->where('ref_no', $statement->ref_no)->first();
  
    return view('accounts-admin.account-statement.showloanreturn', compact('statement', 'loan'));
}

public function addRemarks(Request $request, $id)
{
    $request->validate([
        'remarks' => 'nullable|string|max:1000',
    ]);

    $loan = Loan::findOrFail($id);
    $loan->remarks = $request->remarks;
    $loan->save();

    return back()->with('success', 'Remarks updated successfully!');
}

}
