@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Admin Bank Details</h4>
 @php
    $user = Auth::guard('cr_user')->user();
@endphp
 @if($user && $user->role == 'staff')
    <a href="{{ route('admin-bank.create') }}" class="btn btn-primary">+ Add Bank</a>
    @endif
</div>

@if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif




<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Bank Name</th>
            <th>User Nick Name</th>
            <th>Status / Action</th>
        </tr>
    </thead>
    <tbody>

@forelse($banks as $i => $bank)
<tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $bank->bank_name }}</td>
    <td>{{ $bank->user_nick_name }}</td>
    <td>

        {{-- ================= ADMIN VIEW ================= --}}
        @if($user && $user->role == 'admin')

            @if($bank->status == 'pending')
                <span class="badge bg-warning">Pending</span>

                <a href="{{ route('admin-bank.approve',$bank->id) }}" class="btn btn-sm btn-success">
                    Approve
                </a>

                <a href="{{ route('admin-bank.reject',$bank->id) }}" class="btn btn-sm btn-danger"
                   onclick="return confirm('Reject this bank?')">
                    Reject
                </a>

            @elseif($bank->status == 'approved')
                <span class="badge bg-success">Approved</span>

                <a href="{{ route('admin-bank.edit',$bank->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <a href="{{ route('admin-bank.delete',$bank->id) }}" class="btn btn-sm btn-danger"
                   onclick="return confirm('Delete?')">Delete</a>
                   <a href="{{ url('/account-statement/pending-deletes') }}" class="btn btn-sm btn-warning"> Data Delete approve </a>


                <a href="{{ url('/adminstatment') }}?bank_name={{ urlencode($bank->bank_name) }}" class="btn btn-sm" style="background-color: #6b0000; color: #fbbf24; border: 1px solid #fbbf24;">
                    Bankstatement Import
                </a>

                <a href="#" class="btn btn-sm btn-success">Open Bank</a>

            @elseif($bank->status == 'rejected')
                <span class="badge bg-danger">Rejected</span>
            @endif


        {{-- ================= STAFF VIEW ================= --}}
        {{-- ================= STAFF VIEW ================= --}}
@else

    {{-- Show extra message for staff --}}
    @if($bank->status == 'pending')
        <div class="mb-1">
            <span class="badge bg-warning">Waiting for admin approval ⏳</span>
        </div>
    @elseif($bank->status == 'rejected')
        <div class="mb-1">
            <span class="badge bg-danger">Your bank is rejected ❌</span>
            @if(!empty($bank->reject_reason))
                <br>
                <small class="text-danger">Reason: {{ $bank->reject_reason }}</small>
            @endif
        </div>
    @endif

    {{-- Existing actions should remain as-is --}}
    @if($bank->status == 'approved')
        <span class="badge bg-success">Approved ✅</span>
          <a href="{{ route('admin-bank.edit',$bank->id) }}" class="btn btn-sm btn-warning">Edit</a>
    <a href="{{ route('admin-bank.delete',$bank->id) }}" class="btn btn-sm btn-danger"
       onclick="return confirm('Delete?')">Delete</a>
    @elseif($bank->status == 'pending')
        <!-- <span class="badge bg-secondary">Pending</span> -->
    @elseif($bank->status == 'rejected')
        <span class="badge bg-danger">Rejected</span>
    @endif

    {{-- Keep existing buttons same --}}
  

@endif


    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center">No bank records found</td>
</tr>
@endforelse

    </tbody>
</table>
@endsection
