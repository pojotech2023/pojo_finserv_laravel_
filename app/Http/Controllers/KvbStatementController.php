<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
 use Illuminate\Support\Facades\Log;
class KvbStatementController extends Controller
{
   


public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    $apiUrl = 'https://kvberp.gamssolutions.in/api/bankstatement/import';

    try {
        $response = Http::timeout(120)
            ->attach(
                'file',
                file_get_contents($request->file('file')->getRealPath()),
                $request->file('file')->getClientOriginalName()
            )
            ->post($apiUrl);

        // 🔥 LOG FULL RESPONSE
        Log::info('Bank Import API Response', [
            'status' => $response->status(),
            'body'   => $response->body(),
            'json'   => $response->json(),
        ]);

        if ($response->successful()) {
            return back()->with('success', 'KVB Bank Statement imported successfully !');
        } else {
            return back()->with('error', 'Import failed! API error: ' . $response->body());
        }

    } catch (\Exception $e) {
        // 🔥 LOG EXCEPTION
        Log::error('Bank Import API Exception', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->with('error', 'API call failed: ' . $e->getMessage());
    }
}

    
    
    
    
    
}
