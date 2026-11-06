<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankDetail;
class BankDetailController extends Controller
{
    public function index()
    {
        $banks = BankDetail::all();
        return view('accounts-admin.bank_details.index', compact('banks'));
    }

    public function create()
    {
        return view('accounts-admin.bank_details.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'referred_by' => 'nullable',
        ]);

        BankDetail::create($request->all());

        return redirect()->route('bank-details.index')->with('success', 'Bank details added.');
    }

    public function edit(BankDetail $bankDetail)
    {
        return view('accounts-admin.bank_details.edit', compact('bankDetail'));
    }

    public function update(Request $request, BankDetail $bankDetail)
    {
        $request->validate([
            'bank_name' => 'required',
        ]);

        $bankDetail->update($request->all());

        return redirect()->route('bank-details.index')->with('success', 'Updated successfully.');
    }

    public function destroy(BankDetail $bankDetail)
    {
        $bankDetail->delete();
        return redirect()->route('bank-details.index')->with('success', 'Deleted successfully.');
    }
}
