<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankStatement;
use App\Models\Expanses;
class expansesController extends Controller
{

   public function showexpanses(Request $request, $id)
{
    $statement = \App\Models\BankStatement::findOrFail($id);
    $actionType = $request->query('action_type');

   if ($actionType) {
    if ($statement->action_type !== $actionType) {
        $statement->action_type = $actionType;
        $statement->ref_no = \App\Models\BankStatement::generateRefNo($actionType); // update when action_type changes
    } elseif (empty($statement->ref_no)) {
        $statement->ref_no = \App\Models\BankStatement::generateRefNo($actionType); // create if missing
    }

    $statement->save();
}


  
    $expanses = \App\Models\Expanses::where('statement_id', $id)->first();

    return view('accounts-admin.account-statement.showexpanse', compact('statement','expanses'));
}  

public function saveexpanses(Request $request, $id)
{
    
    $request->validate([
        'expanses_remark' => 'nullable|string|max:255',
        'salary'          => 'nullable|numeric',
        'payout'          => 'nullable|numeric',
        'commision'       => 'nullable|numeric',
        'rent'            => 'nullable|numeric',
        'interest'        => 'nullable|numeric',
        'exrtn'           => 'nullable|numeric',
        'expense_type'    => 'nullable',
        'amount'    => 'nullable',
        'document'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    $statement = \App\Models\BankStatement::findOrFail($id);

    // Insert or update expanses record
    $expanses = \App\Models\Expanses::firstOrNew(['statement_id' => $id]);

    $expanses->statement_id     = $id;
    $expanses->expanses_remark  = $request->expanses_remark;
    $expanses->salary           = $request->salary;
    $expanses->payout           = $request->payout;
    $expanses->commision        = $request->commision;
    $expanses->rent             = $request->rent;
    $expanses->interest         = $request->interest;
    $expanses->exrtn            = $request->exrtn;
    $expanses->expense_type     = $request->expense_type;
    $expanses->amount            = $request->amount;

    // Handle file upload
    if ($request->hasFile('document')) {
        $path = $request->file('document')->store('expanses-documents', 'public');
        $expanses->document = $path;
    }

    $expanses->save();
      $bankStatement = BankStatement::find($id);
      
    if ($bankStatement) {
       
        $bankStatement->conform_ref_no = $request->conform_ref_no;
        $bankStatement->old_action_type = $request->old_action_type; 
         $bankStatement->is_hidden = 1;// store ref_no as conform_ref_no
         $bankStatement->save();
    }

    return redirect()
        ->route('bank.statement' )
        ->with('success', 'Expanses saved successfully.');
}

}
