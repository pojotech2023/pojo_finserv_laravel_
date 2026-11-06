<?php
namespace App\Http\Controllers;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
   public function index()
    {
        $setting = Setting::first(); // only one row
        return view('settings.index', compact('setting'));
    }
    public function save(Request $request)
    {
        $request->validate([
            'bank_import_start_date' => 'required|date',
            'password' => 'required|string',
        ], [
            'password.required' => 'Password is required to update settings',
        ]);
    
        // Get settings row (only one row)
        $setting = Setting::first();
    
        // If first time setup
        if (!$setting) {
            Setting::create([
                'bank_import_start_date' => $request->bank_import_start_date,
                'password' => Hash::make($request->password),
            ]);
    
            return back()->with('success', 'Settings saved successfully (Password created)');
        }
    
        // Verify password
        if (!Hash::check($request->password, $setting->password)) {
            return back()
                ->withErrors(['password' => 'Incorrect password. Date not updated.'])
                ->withInput();
        }
    
        // Password correct → Update date
        $setting->update([
            'bank_import_start_date' => $request->bank_import_start_date
        ]);
    
        return back()->with('success', 'Import start date updated successfully');
    }
}