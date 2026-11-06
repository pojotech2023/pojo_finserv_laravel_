<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Imports\ApplicationFormImport;
use Maatwebsite\Excel\Facades\Excel;

class ApplicationFormController extends Controller
{
    public function showExcelImportForm()
    {
        return view('import_excel');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ApplicationFormImport, $request->file('excel_file'));

            return back()->with('success', 'Excel Data Imported Successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
