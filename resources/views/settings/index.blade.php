@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    @php
        $user = Auth::guard('cr_user')->user();
    @endphp

    <!-- Top Header Row -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">⚙️KVB Bank Settings</h4>

        @if($user && $user->role == 'admin')
            <a href="{{ route('admin-bank.index') }}" class="btn btn-outline-primary">
                🏦 Admin Bank Details
            </a>
        @endif
    </div>

    <div class="row g-4">

        <!-- Bank Import Settings -->
        <div class="col-lg-5 col-md-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white fw-bold">
                 Bhandhan  Bank Import Settings
                </div>

                <div class="card-body">

                    @if ($errors->has('password'))
                    <div class="alert alert-danger">
                    {{ $errors->first('password') }}
                    </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                   <!-- VIEW CODE (settings.blade.php) -->
                   <form method="POST" action="{{ route('settings.save') }}" id="settingsForm">
    @csrf

    <div class="mb-3">
        <label class="form-label fw-semibold">Import Transactions From Date</label>
        <input type="date"
               name="bank_import_start_date"
               class="form-control"
               value="{{ optional($setting)->bank_import_start_date }}"
               required>
    </div>

    <input type="hidden" name="password" id="passwordHidden">

    <button type="submit" class="btn btn-primary w-100">
        Update Settings
    </button>
</form>

<script>

document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault(); // stop normal submit

    Swal.fire({
        title: '🔐 Confirm Password',
        text: 'Enter settings password to change import date',
        input: 'password',
        inputPlaceholder: 'Enter password',
        inputAttributes: {
            autocapitalize: 'off',
            autocorrect: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Verify & Update',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        showLoaderOnConfirm: true,
        preConfirm: (password) => {
            if (!password) {
                Swal.showValidationMessage('Password is required');
            }
            return password;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('passwordHidden').value = result.value;
            e.target.submit(); // submit form
        }
    });
});


</script>

                </div>
            </div>
        </div>

        <!-- Import Bank Statement -->
        

    </div>
</div>
@endsection
