<?php

namespace App\Http\Controllers;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\BankStatement;
use Illuminate\Support\Facades\DB;
class AccountController extends Controller
{
      public function index()
    {
        $accounts = Account::all();
        return view('accounts-admin.accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('accounts-admin.accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'account_number' => 'required',
            'ifsc_code' => 'required',
            'account_holder_name' => 'required',
        ]);

        Account::create($request->all());
        return redirect()->route('accounts.index')->with('success', 'Account added successfully');
    }

    public function edit(Account $account)
    {
        return view('accounts-admin.accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'bank_name' => 'required',
            'account_number' => 'required',
            'ifsc_code' => 'required',
            'account_holder_name' => 'required',
        ]);

        $account->update($request->all());
        return redirect()->route('accounts.index')->with('success', 'Account updated successfully');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully');
    }

   

}
