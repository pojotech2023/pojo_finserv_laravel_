<?php

namespace App\Http\Controllers;
use App\Models\Excess;
use App\Models\BankStatement;
use Illuminate\Http\Request;

class excesscontroller extends Controller
{
    public function showexcess(Request $request, $id)
{
    $statement = BankStatement::findOrFail($id);
    $actionType = $request->query('action_type');

   if ($actionType) {
    if ($statement->action_type !== $actionType) {
        $statement->action_type = $actionType;
        $statement->ref_no = BankStatement::generateRefNo($actionType); // update when action_type changes
    } elseif (empty($statement->ref_no)) {
        $statement->ref_no = BankStatement::generateRefNo($actionType); // create if missing
    }

    $statement->save();
}


  
  
    $excess = Excess::with('files')->where('statement_id', $id)->first();

    return view('accounts-admin.account-statement.showexcess', compact('statement','excess'));
}

public function saveExcess(Request $request, $id)
{

   
    $request->validate([
        'kyc_files.*' => 'file|mimes:jpg,png,pdf|',
        'letter' => 'nullable|file|mimes:jpg,png,pdf|',
    ]);

    // Insert or Update record
    $excess = Excess::updateOrCreate(
        ['statement_id' => $id], // Condition
        [
            
            'user_id' => auth()->id(),
            'updated_at' => now(),
             'ref_no'  =>$request->ref_no,
        ]

    );

    // Handle KYC files
    if ($request->hasFile('kyc_files')) {
        foreach ($request->file('kyc_files') as $file) {
            $path = $file->store('kyc_files', 'public');
            $excess->files()->create([
                'filename' => $file->getClientOriginalName(),
                'filepath' => $path,
            ]);
        }
    }

    // Handle Letter file
    if ($request->hasFile('letter')) {
        $path = $request->file('letter')->store('letters', 'public');
        $excess->letter_path = $path;
        $excess->save();
    }
      $bankStatement = BankStatement::find($id);
      
    if ($bankStatement) {
       
        $bankStatement->conform_ref_no = $request->conform_ref_no;
        $bankStatement->old_action_type = $request->old_action_type; 
        // store ref_no as conform_ref_no
         $bankStatement->save();
    }

    return redirect()
        ->route('bankstatement.saveaction', $id)
        ->with('success', 'Excess details saved successfully.');
}
// return page
public function showReturnPage($statementId)
{
    $statement = BankStatement::findOrFail($statementId);
    $excess = Excess::with('files')->where('ref_no', $statement->ref_no)->first();
  
    return view('accounts-admin.account-statement.showexcessreturn', compact('statement', 'excess'));
}
public function getByRefNo($refNo)
{
    $excess = Excess::with('files')->where('ref_no', $refNo)->first();

    if($excess){
        return response()->json([
            'success' => true,
            'excess' => $excess,
            'files' => $excess->files,
        ]);
    } else {
        return response()->json(['success' => false]);
    }
}

}
