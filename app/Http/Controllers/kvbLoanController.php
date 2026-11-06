<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class kvbLoanController extends Controller
{
    // ⚠️ API PROJECT RUNNING ON 8001 PORT
    private $apiBase = 'https://kvberp.gamssolutions.in/api';

    /* ============================
       VIEW LOAN DETAIL
    ============================ */
    public function show($id)
{
    $response = Http::timeout(10)->get($this->apiBase . "/kvb/loan/{$id}");

    if (!$response->successful()) {
        return back()->with('error', 'API server not responding');
    }

    $loan = $response->json()['data'];

    // Convert main loan to object
    $loan = (object) $loan;

    // Convert statement to object if exists
    if (!empty($loan->statement)) {
        $loan->statement = (object) $loan->statement;
    }

    // Convert files array to collection of objects
    if (!empty($loan->files)) {
        $loan->files = collect($loan->files)->map(fn($f) => (object) $f);
    } else {
        $loan->files = collect();
    }

    // Convert approver if exists
    if (!empty($loan->approver)) {
        $loan->approver = (object) $loan->approver;
    }

    // Convert dates to Carbon
    if (!empty($loan->approved_at)) {
        $loan->approved_at = Carbon::parse($loan->approved_at);
    }

    if (!empty($loan->updated_at)) {
        $loan->updated_at = Carbon::parse($loan->updated_at);
    }

    return view('admin_statement.kvb_bank.approve-details.loan-details', compact('loan'));
}


    /* ============================
       APPROVE LOAN
    ============================ */
    public function approve($id)
    {
        $response = Http::timeout(10)->post($this->apiBase . "/kvb/loan/{$id}/approve", [
            'user_id' => auth()->id() ?? 1,
        ]);

        if (!$response->successful()) {
            return back()->with('error', 'Approval failed from API');
        }

        return back()->with('success', 'Loan approved successfully');
    }

    /* ============================
       REJECT LOAN
    ============================ */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required'
        ]);

        $response = Http::timeout(10)->post($this->apiBase . "/kvb/loan/{$id}/reject", [
            'rejection_reason' => $request->rejection_reason,
        ]);

        if (!$response->successful()) {
            return back()->with('error', 'Rejection failed from API');
        }

        return back()->with('success', 'Loan rejected successfully');
    }

    /* ============================
       ADMIN APPROVAL LIST (API)
    ============================ */
  public function adminApprovalList()
{
    $response = Http::timeout(10)->get($this->apiBase . '/kvb/loans');

    if (!$response->successful()) {
        return back()->with('error', 'Loan API not responding');
    }

    $json = $response->json();

    $pendingLoans  = $this->normalizeLoans($json['pending'] ?? []);
    $approvedLoans = $this->normalizeLoans($json['approved'] ?? []);
    $rejectedLoans = $this->normalizeLoans($json['rejected'] ?? []);

    return view(
        'admin_statement.kvb_bank.approve-details.loan-approvals',
        compact('pendingLoans','approvedLoans','rejectedLoans')
    );
}

/* ------------------ NORMALIZER ------------------ */
private function normalizeLoans($loans)
{
    return collect($loans)->map(function ($loan) {

        // main object
        $loan = (object) $loan;

        // statement
        if (!empty($loan->statement)) {
            $loan->statement = (object) $loan->statement;
        } else {
            $loan->statement = null;
        }

        // approver
        if (!empty($loan->approver)) {
            $loan->approver = (object) $loan->approver;
        }

        // dates → Carbon
        if (!empty($loan->approved_at)) {
            $loan->approved_at = Carbon::parse($loan->approved_at);
        }

        if (!empty($loan->updated_at)) {
            $loan->updated_at = Carbon::parse($loan->updated_at);
        }

        return $loan;
    });
}

}
