<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expanse;

class ExpanseController extends Controller
{
     public function index()
    {
        $expanses = Expanse::latest()->get();
        return view('accounts-admin.expanses.index', compact('expanses'));
    }

    public function create()
    {
        return view('accounts-admin.expanses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable|string',
            'upload_bills' => 'nullable|file|mimes:jpg,png,pdf',
            'gst_number' => 'nullable|string|max:20',
        ]);

        $data = $request->all();

        if ($request->hasFile('upload_bills')) {
            $data['upload_bills'] = $request->file('upload_bills')->store('uploads/bills', 'public');
        }

        Expanse::create($data);

        return redirect()->route('expanses.index')->with('success', 'Expanse added successfully!');
    }

    public function show(Expanse $expanse)
    {
        return view('accounts-admin.expanses.show', compact('expanse'));
    }

    public function edit(Expanse $expanse)
    {
        return view('accounts-admin.expanses.edit', compact('expanse'));
    }

    public function update(Request $request, Expanse $expanse)
    {
        $request->validate([
            'remarks' => 'nullable|string',
            'upload_bills' => 'nullable|file|mimes:jpg,png,pdf',
            'gst_number' => 'nullable|string|max:20',
        ]);

        $data = $request->all();

        if ($request->hasFile('upload_bills')) {
            $data['upload_bills'] = $request->file('upload_bills')->store('uploads/bills', 'public');
        }

        $expanse->update($data);

        return redirect()->route('expanses.index')->with('success', 'Expanse updated successfully!');
    }

    public function destroy(Expanse $expanse)
    {
        $expanse->delete();
        return redirect()->route('expanses.index')->with('success', 'Expanse deleted successfully!');
    }
}
