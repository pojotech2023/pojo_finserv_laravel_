<?php

namespace App\Http\Controllers;
use App\Models\AdminBankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminBankDetailController extends Controller
{
 public function index()
{
    $user = Auth::guard('cr_user')->user();

    if($user->role == 'admin'){
        // Admin sees ALL banks
        $banks = AdminBankDetail::latest()->get();
    } else {
        // Staff sees ALL banks (pending, rejected, approved)
        $banks = AdminBankDetail::latest()->get();

        // If you want only their own records:
        // $banks = AdminBankDetail::where('created_by', $user->id)->latest()->get();
    }

    return view('admin_bank.index', compact('banks'));
}




    public function create()
    {
        return view('admin_bank.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'user_nick_name' => 'required',
            'status' => 'pending'
        ]);

        AdminBankDetail::create($request->all());

        return redirect()->route('admin-bank.index')->with('success','Bank added. Waiting for admin approval.');
    }

    public function edit($id)
    {
        $bank = AdminBankDetail::findOrFail($id);
        return view('admin_bank.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        $bank = AdminBankDetail::findOrFail($id);

        $request->validate([
            'bank_name' => 'required',
            'user_nick_name' => 'required',
        ]);

        $bank->update($request->all());

        return redirect()->route('admin-bank.index')->with('success','Updated successfully');
    }

    public function destroy($id)
    {
        AdminBankDetail::findOrFail($id)->delete();
        return redirect()->back()->with('success','Deleted successfully');
    }
    public function openBank($id)
{
    $bank = AdminBankDetail::findOrFail($id);

    // Static mapping
    if ($bank->bank_name == 'Bandhan Bank') {
        return redirect()->away('https://erpbandhan1.gamssolutions.in/er-login');
    }

    if ($bank->bank_name == 'KVB') {
        return redirect()->away('https://erpbandhan2.gamssolutions.in/er-login');
    }

    return back()->with('error', 'Bank URL not configured');
}
public function approve($id)
{
    $bank = AdminBankDetail::findOrFail($id);
    $bank->status = 'approved';
    $bank->save();

    return back()->with('success','Bank approved successfully');
}

public function reject($id)
{
    $bank = AdminBankDetail::findOrFail($id);
    $bank->status = 'rejected';
    $bank->save();

    return back()->with('success','Bank rejected successfully');
}

}
