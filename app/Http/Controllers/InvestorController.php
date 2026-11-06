<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Models\BankStatement;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $investors = Investor::all();
    return view('accounts-admin.investors.index', compact('investors'));
}

public function create()
{
    return view('accounts-admin.investors.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'investment_duration_value' => 'required|integer|min:1',
        'investment_duration_unit' => 'required|in:day,week,month,year',
        'percentage' => 'nullable|numeric|min:0',
        'payout_time' => 'nullable|string|max:50',
        'account_number' => 'required|string|max:50',
        'ifsc_code' => 'required|string|max:20',
        'branch_name' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'gst_number' => 'nullable|string|max:50',
        'interest_balance' => 'nullable|numeric',
        'interest_status' => 'nullable|string',
            ]);

    // Keep old field for compatibility (if your table still has `investment_duration`)
    $validated['investment_duration'] = $validated['investment_duration_value'] . ' ' . $validated['investment_duration_unit'];
    Investor::create($validated);

    return redirect()->route('investors.index')->with('success', 'Investor added!');
}


public function edit(Investor $investor)
{
    return view('accounts-admin.investors.edit', compact('investor'));
}

public function update(Request $request, Investor $investor)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'investment_duration_value' => 'required|integer|min:1',
        'investment_duration_unit' => 'required|string|max:255',
        'percentage' => 'nullable|numeric',
        'payout_time' => 'nullable|string|max:255',
        'account_number' => 'required|string|max:255',
        'ifsc_code' => 'required|string|max:255',
        'branch_name' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'gst_number' => 'nullable|string|max:255',
        'received_commission' => 'nullable|numeric',
        'interest_balance' => 'nullable|numeric',
    ]);

    // Combine value + unit into one string
    $validated['investment_duration'] = $validated['investment_duration_value'] . ' ' . $validated['investment_duration_unit'];

    // Remove temporary form-only fields
    unset($validated['investment_duration_value'], $validated['investment_duration_unit']);

    $investor->update($validated);

    return redirect()->route('investors.index')->with('success', 'Investor updated!');
}

public function destroy(Investor $investor)
{
    $investor->delete();
    return redirect()->route('investors.index')->with('success', 'Investor deleted!');
}

public function search(Request $request)
{
    $term = $request->get('term');

    $names = Investor::where('name', 'LIKE', "%{$term}%")
        ->limit(10) // only top 10 results
        ->pluck('name');

    return response()->json($names);
}
public function getDetails(Request $request)
{
    $investor = Investor::where('name', $request->name)->first();
    return response()->json($investor);
}


public function updateInterest(Request $request)
{
    $investor = Investor::findOrFail($request->investor_id);

    $investmentDuration = $request->investment_duration_value . ' ' . $request->investment_duration_unit;

    $investor->percentage = $request->percentage;
    $investor->investment_duration = $investmentDuration;
    $investor->interest_amount = $request->interest_amount;
    $investor->received_commission = $request->received_commission ?? 0;
    $investor->interest_balance = $request->interest_balance ?? 0;
    $investor->statement_id = $request->statement_id;
    $investor->ref_no = $request->ref_no;
    $investor->duration_status = $request->duration_status;
    $investor->save();

    // ✅ BEST FIX: Always set INVESTMENT for investor updates
    BankStatement::where('id', $request->statement_id)
        ->update([
            'conform_ref_no' => $request->ref_no,  // Use ref_no instead
            'old_action_type' => 'INVESTMENT'      // Hardcode it!
        ]);

    return response()->json([
        'success' => true,
        'redirect' => route('bank.statement')
    ]);
}  // duration calculate panna
    
public function pendingInterestList()
{
    $pendingInvestments = Investor::where('interest_status', 'pending')
        ->with('bankStatement')   // eager load bank statement
        ->orderByDesc('created_at')
        ->get();
 
    $pendingCount = $pendingInvestments->count();
 
    return view(
        'accounts-admin.account-statement.investment_pending_interest',
        compact('pendingInvestments', 'pendingCount')
    );
}
 
// ── Mark interest as received → clear pending status ──────────────────
public function markInterestPaid($id)
{
    $investor = Investor::findOrFail($id);
    $investor->interest_status = 'paid';
    $investor->save();
 
    return redirect()
        ->route('investment.pending.interest')
        ->with('success', "Interest for {$investor->name} marked as received.");
}

public function updateRemarks(Request $request, $id)
{
    $request->validate([
        'remarks' => 'nullable|string'
    ]);

    $investor = Investor::findOrFail($id);
    $investor->remarks = $request->remarks;
    $investor->save();

    return response()->json([
        'success' => true,
        'message' => 'Remark updated successfully!'
    ]);
}

public function searchRemarks(Request $request)
{
    $q = $request->input('q', '');

    $remarks = Investor::whereNotNull('remarks')
        ->where('remarks', '!=', '')
        ->where('remarks', 'LIKE', "%{$q}%")
        ->distinct()
        ->limit(10)
        ->pluck('remarks');

    return response()->json($remarks);
}
}
