<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 
use App\Models\Employee;

class EmployeeController extends Controller
{
     public function index()
    {
        $employees = Employee::all();
        return view('accounts-admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('accounts-admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'aadhaar_number' => 'required',
            'mobile_number' => 'required',
            'document' => 'nullable|file|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $path = $file->store('documents', 'public');
            $data['document_path'] = $path;
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        return view('accounts-admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required',
            'aadhaar_number' => 'required',
            'mobile_number' => 'required',
            'document' => 'nullable|file|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $path = $file->store('documents', 'public');
            $data['document_path'] = $path;
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }
}
