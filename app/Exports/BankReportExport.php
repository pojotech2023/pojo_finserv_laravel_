<?php

namespace App\Exports;

use App\Models\BankStatement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BankReportExport implements FromView
{
    protected $category;
    protected $type;
    protected $request;

    public function __construct($category, $type, $request)
    {
        $this->category = strtolower($category);
        $this->type = strtoupper($type);
        $this->request = $request;
    }

    public function view(): View
    {
        $query = BankStatement::query()->where('is_hidden', 1)
            ->where('cr_dr', $this->type);

        $prefix = match($this->category) {
            'investment' => 'INV',
            'loan'       => 'LOA',
            'excess'     => 'UW',
            'others'     => 'EXP',
            default      => null,
        };

        if ($prefix) {
            $query->where('conform_ref_no', 'like', $prefix . '%');
        }

        if ($this->request->filled('from_date') && $this->request->filled('to_date')) {
            $query->whereBetween('date', [$this->request->from_date, $this->request->to_date]);
        }

        $statements = $query->orderBy('date', 'desc')->get();

        return view('accounts-admin.reports.export_excel', [
            'statements' => $statements,
            'category' => ucfirst($this->category),
            'type' => ucfirst($this->type)
        ]);
    }
}
