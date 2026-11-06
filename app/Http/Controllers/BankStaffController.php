<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankStaff;

class BankStaffController extends Controller
{
     public function autocomplete(Request $request)
    {
        $q = $request->input('q', '');

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $results = BankStaff::where('staff_name', 'LIKE', "%{$q}%")
            ->select('staff_name', 'bank_name', 'percentage')
            ->orderBy('staff_name')
            ->limit(10)
            ->get();

        return response()->json($results);
    }

    /**
     * ✅ Bank Name Autocomplete
     * Route: GET /bank-name/autocomplete?q=...
     * Name:  bank-name.autocomplete
     *
     * Returns: ["SBI", "HDFC", "ICICI", ...]
     */
    public function bankAutocomplete(Request $request)
    {
        $q = $request->input('q', '');

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $banks = BankStaff::where('bank_name', 'LIKE', "%{$q}%")
            ->distinct()
            ->orderBy('bank_name')
            ->pluck('bank_name');

        return response()->json($banks);
    }
    
public function index()
    {
        $staff = BankStaff::all();
        return view('accounts-admin.bank_staff.index', compact('staff'));
    }

    public function create()
    {
        return view('accounts-admin.bank_staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_name' => 'required',
            'bank_name' => 'required',
            'employee_designation' => 'required',
            'document' => 'nullable|file|max:2048',
        ]);

        $data = $request->all();
        $data['percentage'] = $request->input('percentage', 0);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('bank_staff_docs', 'public');
            $data['document_path'] = $path;
        }

        BankStaff::create($data);

        return redirect()->route('bank-staff.index')->with('success', 'Staff created successfully.');
    }

    public function edit(BankStaff $bankStaff)
    {
        return view('accounts-admin.bank_staff.edit', compact('bankStaff'));
    }

    public function update(Request $request, BankStaff $bankStaff)
    {
        $request->validate([
            'staff_name' => 'required',
            'bank_name' => 'required',
            'employee_designation' => 'required',
        ]);

        $data = $request->all();
        $data['percentage'] = $request->input('percentage', 0);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('bank_staff_docs', 'public');
            $data['document_path'] = $path;
        }

        $bankStaff->update($data);

        return redirect()->route('bank-staff.index')->with('success', 'Updated successfully.');
    }

    public function destroy(BankStaff $bankStaff)
    {
        $bankStaff->delete();
        return redirect()->route('bank-staff.index')->with('success', 'Deleted successfully.');
    }
}
