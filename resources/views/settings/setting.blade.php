@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    @php
        $user = Auth::guard('cr_user')->user();
    @endphp

    <!-- Top Header Row -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">⚙️{{ request('bank_name', 'Bank') }} Settings</h4>

        @if($user && $user->role == 'admin')
            <a href="{{ url('/settings') }}" class="btn btn-outline-primary">
                🏦 Date Setting
            </a>
            <a href="{{ url('admin/loan-approvals') }}" class="btn btn-outline-primary">
                🏦 Loan Approval Status
            </a>
           
        @endif
    </div>

    <div class="row g-4">


        <!-- Import Bank Statement -->
        <div class="col-lg-7 col-md-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
                    <span>Import Bank Statement</span>
                    <a href="{{ route('bankstatement.downloadTemplate') }}" class="btn btn-sm btn-light text-success fw-bold shadow-sm">
                        <i class="fas fa-file-excel me-1"></i> Download Sample Excel Template
                    </a>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('bankstatement.import') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="p-3 border rounded bg-light">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Choose Excel File</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success">
                            ⬆️ Import Bank Statement
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
