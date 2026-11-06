<?php

use App\Models\ApplicationForm;
use App\Models\Login;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\KYCController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\CrAuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BankDetailController;
use App\Http\Controllers\BankStaffController;
use App\Http\Controllers\ExpanseController;
use App\Http\Controllers\BankStatementController;
use App\Http\Controllers\loancontroller;
use App\Http\Controllers\excesscontroller;
use App\Http\Controllers\expansesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminBankDetailController;
use App\Http\Controllers\KvbStatementController;
use App\Http\Controllers\kvbLoanController;
use App\Models\Investor;
use App\Models\Account;
use App\Models\Employee;
use App\Models\BankStaff;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/service/{type?}', function ($type = null) {
    if ($type) {
        return view('service-detail', ['service_type' => $type]);
    }
    return view('service');
})->name('service');

Route::get('/investment', function () {
    return view('investment');
})->name('investment');

Route::get('/loan', function () {
    return view('loan');
})->name('loan');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact-submit', [KYCController::class, 'submitContactEnquiry'])->name('contact.submit');
Route::post('/quick-enquiry', [KYCController::class, 'submitQuickEnquiry'])->name('quick.enquiry.submit');
Route::get('/admin/contact-enquiries', [KYCController::class, 'contactEnquiriesList'])->name('admin.contact.enquiries');
Route::delete('/admin/contact-enquiries/{id}', [KYCController::class, 'destroyContactEnquiry'])->name('admin.contact.destroy');

// S2D / Customer Login Routes
Route::get('/customerlogin', [KYCController::class, 'aadhaarForm'])->name('customerlogin');
Route::post('/aadhaar/send-otp', [KYCController::class, 'sendOtp']);
Route::get('/aadhaar/enter-otp/{request_id}', [KYCController::class, 'showOtpForm'])->name('aadhaar.otp.form');
Route::post('/aadhaar/verify-otp/{request_id}', [KYCController::class, 'verifyOtp'])->name('aadhaar.verifyOtp');
Route::get('/pan/upload', [KYCController::class, 'show'])->name('pancard.upload');
Route::get('/upload-pan/{kyc_id}', [KYCController::class, 'showPanUploadForm'])->name('pan.upload.form');
Route::post('/upload-pan', [KYCController::class, 'submitPanUpload'])->name('pan.upload.submit');
Route::post('/upload-pan-image', [KYCController::class, 'submitPanimageUpload'])->name('panimage.upload.submit');
Route::get('/promissory-form/{kyc_id}', [KYCController::class, 'showPromissoryEditable'])->name('promissory.form');
Route::post('/promissory-store', [KYCController::class, 'storePromissoryNote'])->name('promissory.store');
Route::get('/amount/{kyc_id}', [KYCController::class, 'showamount'])->name('amount.form');
Route::post('/amount', [KYCController::class, 'amountinsert'])->name('aadhaar.amount');
Route::get('/upload-bill/{kyc_id}', [KYCController::class, 'showloanUploadForm'])->name('loan.upload.form');
Route::post('/upload-bill', [KYCController::class, 'submitloanUpload'])->name('loan.upload.submit');
Route::get('/kyc-download/{id}', [KYCController::class, 'downloadPDF'])->name('kyc.download');
Route::get('/upload-cheque/{kyc_id}', [KYCController::class, 'showchequeUploadForm'])->name('cheque.upload.form');
Route::post('/upload-cheque', [KYCController::class, 'submitchequeUpload'])->name('cheque.upload.submit');
Route::get('/upload-aadhar', [KYCController::class, 'showaadharUploadForm'])->name('aadhar.upload.form');
Route::post('/upload-aadhar', [KYCController::class, 'submitaadharUpload'])->name('aadhar.upload.submit');
Route::post('/upload-aadhar-image', [KYCController::class, 'submitaadharimageUpload'])->name('aadharimage.upload.submit');
Route::get('/thankyou', function () {
    return view('thankyou');
})->name('thankyou');

Route::get('/adminlogin', function (Request $request) {
    if (!Auth::guard('cr_user')->check()) {
        return redirect()->route('cr.login');
    }
    return app(KYCController::class)->index($request);
})->name('kyc.index');

Route::get('/admin', function () {
    return redirect()->route('kyc.index');
})->name('admin.home');

Route::get('/fix-server', function () {
    $results = [];
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        $results['view_clear'] = trim(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Throwable $e) {
        $results['view_clear'] = 'SKIPPED: ' . $e->getMessage();
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        $results['cache_clear'] = trim(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Throwable $e) {
        $results['cache_clear'] = 'SKIPPED: ' . $e->getMessage();
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        $results['config_clear'] = trim(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Throwable $e) {
        $results['config_clear'] = 'SKIPPED: ' . $e->getMessage();
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $results['route_clear'] = trim(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Throwable $e) {
        $results['route_clear'] = 'SKIPPED: ' . $e->getMessage();
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $results['migrate'] = trim(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Throwable $e) {
        $results['migrate'] = 'SKIPPED: ' . $e->getMessage();
    }

    // Auto-seed admin user if empty
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('cr_users') && \App\Models\CrUser::count() === 0) {
            \App\Models\CrUser::create([
                'name' => 'Admin',
                'email' => 'admin@demo.com',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
            ]);
            $results['admin_user_seed'] = 'Created default admin@demo.com';
        }
    } catch (\Throwable $e) {
        $results['admin_user_seed'] = $e->getMessage();
    }

    $results['db_status'] = [
        'app_configs_table'       => \Illuminate\Support\Facades\Schema::hasTable('app_configs'),
        'contact_enquiries_table' => \Illuminate\Support\Facades\Schema::hasTable('contact_enquiries'),
        'k_y_c_s_table'           => \Illuminate\Support\Facades\Schema::hasTable('k_y_c_s'),
        'k_y_c_s_service_type'    => \Illuminate\Support\Facades\Schema::hasColumn('k_y_c_s', 'service_type'),
        'cr_users_table'          => \Illuminate\Support\Facades\Schema::hasTable('cr_users'),
        'cr_users_count'          => \Illuminate\Support\Facades\Schema::hasTable('cr_users') ? \App\Models\CrUser::count() : 0,
    ];

    return response()->json([
        'status' => 'OK',
        'message' => 'POJO FINSERV server diagnostic and auto-repair complete!',
        'details' => $results,
        'login_url' => url('/admin-login'),
        'admin_url' => url('/adminlogin'),
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});
Route::post('/admin/toggle-api-mode', [KYCController::class, 'toggleApiMode'])->name('admin.toggleApiMode');
Route::post('/admin/reset-api-hits', [KYCController::class, 'resetApiHits'])->name('admin.resetApiHits');
Route::get('/kyc-export', [KYCController::class, 'exportExcel'])->name('kyc.export');
Route::delete('/kyc/{id}', [KYCController::class, 'destroy'])->name('kyc.destroy');
Route::post('/aadhaar/resend-otp', [KYCController::class, 'resendOtp'])->name('aadhaar.resendOtp');
Route::get('/eye-blink/{kyc_id}', [KYCController::class, 'showeyeblinkForm'])->name('eye.blink.form');
Route::post('/store-photo', [KYCController::class, 'store'])->name('photo.store');
Route::get('/admin/promissory/download/{id}', [KYCController::class, 'downloadPromissoryImage'])->name('admin.promissory.download');
Route::get('/kycs/{id}', [KYCController::class, 'detailsshow'])->name('kyc.show');
Route::get('/auth/google', [KYCController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [KYCController::class, 'handleGoogleCallback']);
Route::post('/promissory/send-otp', [KYCController::class, 'promissorySendOtp'])->name('promissory.sendOtp');
Route::post('/promissory/verify-otp', [KYCController::class, 'promissoryVerifyOtp'])->name('promissory.verifyOtp');







// routes/web.php


//cr-login
// Route::middleware(['auth:cr_user','cr_role:admin'])->group(function () {
//     Route::get('/admin/dashboard', fn() => view('cr.admin.dashboard'));
// });

// Route::middleware(['auth:cr_user','cr_role:staff'])->group(function () {
//     Route::get('/staff/dashboard', fn() => view('cr.staff.dashboard'));
// });

Route::get('admin-login', [CrAuthController::class, 'showLoginForm'])->name('cr.login');
Route::get('login', [CrAuthController::class, 'showLoginForm'])->name('login');
Route::get('er-login', [CrAuthController::class, 'showLoginForm']);

Route::post('cr-login', [CrAuthController::class, 'login']);
Route::get('cr-dashboard', [BankStatementController::class, 'dashboard'])->middleware('auth:cr_user')->name('cr.dashboard');
Route::post('cr-logout', [CrAuthController::class, 'logout'])->name('cr.logout');
Route::get('sign_in', [CrAuthController::class, 'showLoginForm'])->name('Sign_In');

//Accounts
// web.php

    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings/save', [SettingsController::class, 'save'])->name('settings.save');





Route::middleware('auth:cr_user')->group(function () {
    Route::post('/bank-statement/manual-categorize/{id}',
    [BankStatementController::class, 'manualCategorize'])
    ->name('bank.statement.manualCategorize');
 
Route::get('/bank-statement/manual-categorized',
    [BankStatementController::class, 'manualCategorizedList'])
    ->name('bank.statement.manualCategorizedList');
 
Route::post('/bank-statement/remove-manual-category/{id}',
    [BankStatementController::class, 'removeManualCategory'])
    ->name('bank.statement.removeManualCategory');
    Route::get('/investments/open', [BankStatementController::class, 'openInvestments'])
    ->name('investment.open');

    Route::resource('accounts', AccountController::class);
    Route::resource('investors', InvestorController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('bank-details', BankDetailController::class);
    Route::resource('bank-staff', BankStaffController::class);
    Route::resource('expanses', ExpanseController::class);
    Route::post('/bankstatement/import', [BankStatementController::class, 'import'])->name('bankstatement.import');
    Route::get('/bankstatement/download-template', [BankStatementController::class, 'downloadSampleTemplate'])->name('bankstatement.downloadTemplate');
    Route::get('bank-statement', [BankStatementController::class, 'getform'])->name('bank.statement');
    
    //inverstor
    Route::get('/bank-statement/set-action/{id}', [BankStatementController::class, 'setAction'])->name('bankstatement.setAction');
    Route::get('/bank-statement/{statement}', [BankStatementController::class, 'setAction'])->name('bankstatement.show');
    Route::post('/investor/update-data', [BankStatementController::class, 'updateInvestorData'])->name('investor.update.data');
    Route::get('/investor/search', function (Illuminate\Http\Request $request) {
        $search = $request->q;
        $names = \App\Models\Investor::where('name', 'LIKE', "%{$search}%")
            ->limit(10)
            ->pluck('name');
        return response()->json($names);
    });
    Route::get('/investor/details', [InvestorController::class, 'getDetails']);
    Route::post('/investors/update-interest', [InvestorController::class, 'updateInterest'])->name('investors.updateInterest');
    Route::post('/investors/{id}/update-interest', [InvestorController::class, 'updateInterest'])
    ->name('investors.updateInterest');
    // loan
    Route::get('/bank-statement/showloan/{id}', [loancontroller::class, 'showLoan'])->name('bankstatement.showLoan');
    Route::post('/bank-statement/{statement}/saveloan', [loancontroller::class, 'saveLoan'])->name('bank-statement.saveloan');
 // web.php



Route::get('/bank-name/autocomplete', [BankStaffController::class, 'bankAutocomplete'])
     ->name('bank-name.autocomplete');

   

    Route::post('/bank-statement/save-action', [BankStatementController::class, 'saveAction'])->name('account.statement.save');
// excess
     Route::get('/bank-statement/excess/{id}', [excessController::class, 'showexcess'])->name('bankstatement.saveaction');
     Route::post('/bank-statement/{id}/excess', [excessController::class, 'saveExcess'])
    ->name('bank-statement.saveexcess');
    //expanse
    Route::get('/bank-statement/expanses/{id}', [expansesController::class, 'showexpanses'])->name('bankstatement.saveexpanse');
    Route::post('/bank-statement/{id}/expanses', [expansesController::class, 'saveexpanses'])
    ->name('bank-statement.saveexpanses');
    //investor  Return
   Route::get('/bank-statement/showreturn/{id}', [BankStatementController::class, 'showReturn'])
    ->name('bank-statement.showreturn');

    // Save the Investment Return data
    Route::post('/bank-statement/savereturn/{id}', [BankStatementController::class, 'saveReturn'])
        ->name('bank-statement.savereturn');

    Route::get('/investors/return', [BankStatementController::class, 'showByRefNo'])->name('investors.return');
    Route::post('/investors/upload-kyc/{id}', [BankStatementController::class, 'uploadKyc'])->name('investors.uploadKyc');
    Route::get('/get-investor-by-ref/{refNo}', [InvestorController::class, 'getInvestorByRef']);

    Route::get('/bank-statement/showloanreturn/{id}', [loancontroller::class, 'showReturnPage'])
        ->name('bank-statement.showloanreturn');
    Route::get('/loan/return', [loancontroller::class, 'showByRefNo'])->name('loan.return');
    Route::post('/loan/upload-kyc/{id}', [BankStatementController::class, 'uploadKyc'])->name('loan.uploadKyc');
    Route::delete('/loan/kyc/{fileId}', [BankStatementController::class, 'deleteKyc'])->name('loan.kyc.delete');
   
    Route::get('/loan/by-ref/{refNo}', [loancontroller::class, 'getByRefNo']);
    Route::post('/loan/save', [loancontroller::class, 'savekyc'])->name('loan.save');

    Route::get('/bank-statement/showexcessreturn/{id}', [excessController::class, 'showReturnPage'])
        ->name('bank-statement.showexcessreturn');
    Route::get('/excess/by-ref/{refNo}', [excessController::class, 'getByRefNo']);
    Route::get('/bank-statement/next-ref', [BankStatementController::class, 'getNextRef']);


Route::get('/staff-percentage', [BankStatementController::class, 'getStaffPercentage']);

    // catgories checking place
Route::get('/staff-name/autocomplete', [BankStaffController::class, 'autocomplete'])
     ->name('staff-name.autocomplete');

Route::get('/bank-name/autocomplete', [BankStaffController::class, 'bankAutocomplete'])
     ->name('bank-name.autocomplete');
    Route::post('/bank-statement/save-refno/{id}', [BankStatementController::class, 'saveRefNo'])->name('bank.saveRefNo');
    Route::get('/categorized', [BankStatementController::class, 'category'])->name('categorized');
   Route::post('/bank-statement/remove-refno/{id}', [BankStatementController::class, 'removeRefNo'])
     ->name('bankstatement.removeRefNo');
    Route::get('/bankstatement/view/{refNo}', [BankStatementController::class, 'viewByRefNo'])->name('bankstatement.view');
    //move to uncatgorized section
    Route::get('/bankstatement/uncategorized/{conformRefNo}', [BankStatementController::class, 'toggleUncategorized'])->name('bankstatement.uncategorized');
    //move open investment to categorized section
    Route::get('/bankstatement/categorize-investment/{conformRefNo}', [BankStatementController::class, 'categorizeOpenInvestment'])->name('bankstatement.categorizeInvestment');
    //move open investment to uncategorized section
    Route::get('/bankstatement/uncategorize-investment/{conformRefNo}', [BankStatementController::class, 'uncategorizeOpenInvestment'])->name('bankstatement.uncategorizeInvestment');
    //uncategorizedReport
     Route::get('uncategorizedCreditReport', [BankStatementController::class, 'uncategorizedCreditReport'])->name('bank.uncategorizedCreditReport');
     Route::get('uncategorizedDebitReport', [BankStatementController::class, 'uncategorizedDebitReport'])->name('bank.uncategorizedDebitReport');
     Route::get('/export/uncategorized/credit', [BankStatementController::class, 'exportUncategorizedCreditExcel'])->name('export.uncategorized.credit');
     Route::get('/export/uncategorized/debit', [BankStatementController::class, 'exportUncategorizedDebitExcel'])->name('export.uncategorized.debit');
   //categorized reportRoute::get(

   Route::get('categorizedCreditReport', [BankStatementController::class, 'categorizedCreditReport'])->name('categorized.credit.report');
    Route::get('categorized-debit-Report', [BankStatementController::class, 'categorizedDebitReport'])->name('export.categorizedDebitReport.credit');
   
// Categorized Credit Reports
// Credit Report
Route::get('/report/{category}/credit', [BankStatementController::class, 'categorizedCreditReport'])
    ->name('report.category.credit');
Route::get('/test-investor/{refNo}', [BankStatementController::class, 'testInvestorFetch'])
    ->name('testInvestorFetch');
// Debit Report
Route::get('/report/{category}/debit', [BankStatementController::class, 'categorizedDebitReport'])
    ->name('report.category.debit');

// Balance Report
Route::get('/report/{category}/balance', [BankStatementController::class, 'categorizedBalanceReport'])
    ->name('report.category.balance');
 
Route::get('/report/utr-search', [BankStatementController::class, 'utrSearch'])
    ->name('report.utr.search');
 
Route::get('/report/utr-search/download', [BankStatementController::class, 'utrSearchDownload'])
    ->name('report.utr.download');
Route::get('/report/{category}/credit/export', [BankStatementController::class, 'exportCreditReport'])
    ->name('report.category.credit.export');
Route::get('/report/{category}/debit/export', [BankStatementController::class, 'exportDebitReport'])
    ->name('report.category.debit.export');


   Route::prefix('report')->group(function () {
    Route::get('/{type}/credit', [BankStatementController::class, 'creditReport']);
    Route::get('/{type}/debit', [BankStatementController::class, 'debitReport']);
    Route::get('/{type}/balance', [BankStatementController::class, 'balanceReport']);
    Route::get('/unmatched-loans', [BankStatementController::class, 'unmatchedLoans'])->name('unmatched.loans');
   
});

Route::post('/unmatched-loans/mark-cleared/{refNo}', [BankStatementController::class, 'markMismatchCleared'])->name('unmatched.markCleared');
Route::post('/unmatched-loans/upload-kyc/{loanId}', [BankStatementController::class, 'uploadKyc'])->name('unmatched.uploadKyc');
Route::get('/unmatched-loans/kyc-files/{loanId}', [BankStatementController::class, 'getKycFiles'])->name('unmatched.kycFiles');



Route::get('/admin/loan-approvals', [loancontroller::class, 'adminApprovalList'])->name('admin.loan.approvals');
    Route::get('/admin/loan/{loanId}/detail', [loancontroller::class, 'viewLoanDetail'])->name('admin.loan.detail');
    Route::post('/admin/loan/{loanId}/approve', [loancontroller::class, 'approveLoan'])->name('admin.loan.approve');
    Route::post('/admin/loan/{loanId}/reject', [loancontroller::class, 'rejectLoan'])->name('admin.loan.reject');

Route::post('/loan/{id}/remarks', [loancontroller::class, 'addRemarks'])->name('loan.addRemarks');


 Route::get('/admin-bank', [AdminBankDetailController::class,'index'])->name('admin-bank.index');
    Route::get('/admin-bank/create', [AdminBankDetailController::class,'create'])->name('admin-bank.create');
    Route::post('/admin-bank/store', [AdminBankDetailController::class,'store'])->name('admin-bank.store');
    Route::get('/admin-bank/edit/{id}', [AdminBankDetailController::class,'edit'])->name('admin-bank.edit');
    Route::post('/admin-bank/update/{id}', [AdminBankDetailController::class,'update'])->name('admin-bank.update');
    Route::get('/admin-bank/delete/{id}', [AdminBankDetailController::class,'destroy'])->name('admin-bank.delete');
Route::get('/admin-bank/approve/{id}', [AdminBankDetailController::class, 'approve'])->name('admin-bank.approve');
Route::get('/admin-bank/reject/{id}', [AdminBankDetailController::class, 'reject'])->name('admin-bank.reject');
Route::get('/adminstatment', function () {
     return view('settings.setting');
})->name('date.setting');


//kvb banks statement

Route::get('/kvbstatment', function () {
     return view('admin_statement.kvb_bank.kvb_bankstatement');
})->name('kvb.bank');

Route::post('/kvbstatement/upload', [KvbStatementController::class, 'upload'])
     ->name('bankstatement.kvb');
  Route::get('/kvbdate', function () {
     return view('admin_statement.kvb_bank.setting.index');
})->name('kvb.date');
   

Route::get('/admin/kvb/loan', [kvbLoanController::class, 'adminApprovalList'])->name('admin.kvb.loan.detail');
Route::get('/admin/kvb/loan/{id}', [kvbLoanController::class, 'show'])->name('admin.loan.kvb.detail');

    Route::post('/admin/kvb/loan/{id}/approve', [kvbLoanController::class, 'approve'])->name('admin.loan.kvb.approve');

    Route::post('/admin/kvb/loan/{id}/reject', [kvbLoanController::class, 'reject'])->name('admin.loan.kvb.reject');


 Route::get('/loan/interest-status-list/export', [BankStatementController::class, 'exportLoanInterestList'])->name('loan.interest.list.export');

});
 //bankstatement delete
 Route::post('/account-statement/delete-by-date', [BankStatementController::class, 'deleteByDateRange'])->name('account.statement.deleteByDate');

// Trashed records listing
Route::get('/account-statement/trashed', [BankStatementController::class, 'trashedRecords'])->name('account.statement.trashed');

// Restore single record
Route::post('/account-statement/restore/{id}', [BankStatementController::class, 'restoreRecord'])->name('account.statement.restore');

// Restore by date range
Route::post('/account-statement/restore-by-date', [BankStatementController::class, 'restoreByDateRange'])->name('account.statement.restoreByDate');

// Permanent delete
Route::post('/account-statement/{id}/request-delete', [BankStatementController::class, 'requestDelete'])
    ->name('account.statement.request.delete');
Route::get('/account-statement/pending-deletes', [BankStatementController::class, 'pendingDeletes'])
    ->name('account.statement.pending.deletes');

Route::post('/account-statement/{id}/approve-delete', [BankStatementController::class, 'approveDelete'])
    ->name('account.statement.approve.delete');

Route::post('/account-statement/{id}/reject-delete', [BankStatementController::class, 'rejectDelete'])
    ->name('account.statement.reject.delete');
Route::delete('/account-statement/force-delete/{id}', [BankStatementController::class, 'forceDeleteRecord'])->name('account.statement.forceDelete');
  
Route::delete('/account-statement/soft-delete/{id}', [BankStatementController::class, 'softDeleteSingle'])->name('account.statement.softDeleteSingle');

Route::get('/investments/pending-interest', 
    [InvestorController::class, 'pendingInterestList'])
    ->name('investment.pending.interest');

Route::patch('/investments/{id}/mark-interest-paid', 
    [InvestorController::class, 'markInterestPaid'])
    ->name('investment.markInterestPaid');
Route::get('/investments', [InvestorController::class, 'index'])
    ->name('investment.list');
// loan intrest penting list
Route::get('/loan/interest-status-list',       [BankStatementController::class, 'loanInterestStatusList'])->name('loan.interest.list');
Route::get('/loan/{loanId}/interest-status',   [BankStatementController::class, 'loanInterestStatus'])->name('loan.interest.status');
Route::patch('/loan/{loanId}/interest-accept', [BankStatementController::class, 'acceptLoanInterest'])->name('loan.interest.accept');
Route::patch('/loan/{loanId}/interest-reject', [BankStatementController::class, 'rejectLoanInterest'])->name('loan.interest.reject');

// excel upload form
//Route::get('/form-import', [ApplicationFormController::class, 'showExcelImportForm'])->name('form-import');
