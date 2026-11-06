@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-envelope-open-text text-primary me-2"></i> Customer Contact Enquiries
            </h2>
            <p class="text-muted small mb-0">View customer requirements and inquiries submitted through <strong>POJO FINSERV</strong> website Contact Us and Quick Enquiry forms.</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <span class="badge bg-primary fs-6 px-3 py-2 shadow-sm">Total Enquiries: {{ $enquiries->total() }}</span>
            <a href="{{ route('kyc.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-3">
                <i class="fas fa-users-cog me-1"></i> Customer Applications
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm py-3 px-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
                <div>
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(90deg, #072348 0%, #003b95 100%);">
            <h5 class="fw-bold mb-0 text-white"><i class="fas fa-inbox me-2 text-warning"></i> Customer Enquiries List</h5>
            <span class="badge bg-light text-dark px-3 py-2 fs-6 fw-bold">Records: {{ $enquiries->total() }}</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="bg-light text-secondary" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="py-3">#</th>
                            <th class="py-3 text-start">Customer Name</th>
                            <th class="py-3">Mobile Number</th>
                            <th class="py-3">Email Address</th>
                            <th class="py-3">Service Required</th>
                            <th class="py-3 text-start">Message / Requirement</th>
                            <th class="py-3">Date Submitted</th>
                            <th class="py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.92rem;">
                        @forelse($enquiries as $index => $item)
                        <tr>
                            <td class="fw-bold text-secondary">
                                {{ ($enquiries->currentPage() - 1) * $enquiries->perPage() + $index + 1 }}
                            </td>
                            <td class="text-start fw-bold text-dark">
                                <i class="fas fa-user-circle text-primary me-1"></i> {{ $item->name }}
                            </td>
                            <td>
                                <a href="tel:{{ $item->phone }}" class="text-decoration-none fw-bold text-success">
                                    <i class="fas fa-phone-alt me-1"></i> {{ $item->phone }}
                                </a>
                            </td>
                            <td>
                                @if($item->email)
                                    <a href="mailto:{{ $item->email }}" class="text-decoration-none text-dark">
                                        <i class="fas fa-envelope text-muted me-1"></i> {{ $item->email }}
                                    </a>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary font-monospace px-2.5 py-1.5 fw-bold">
                                    {{ $item->service_required ?? 'General Inquiry' }}
                                </span>
                            </td>
                            <td class="text-start small text-secondary" style="max-width: 320px;">
                                {{ $item->message ?? 'No message provided.' }}
                            </td>
                            <td class="small text-muted">
                                {{ $item->created_at ? $item->created_at->format('d-m-Y h:i A') : 'N/A' }}
                            </td>
                            <td>
                                <form action="{{ route('admin.contact.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this enquiry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle shadow-sm" title="Delete Enquiry" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 text-secondary d-block"></i>
                                No customer contact enquiries found yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($enquiries->hasPages())
        <div class="card-footer bg-white py-3 px-4">
            {{ $enquiries->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
