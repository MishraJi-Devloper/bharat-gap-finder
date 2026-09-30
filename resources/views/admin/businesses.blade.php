@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
    <div>
        <span class="badge bg-warning-subtle text-warning-emphasis mb-2">Admin review queue</span>
        <h1 class="h3 fw-bold text-dark mb-1">MSME verification</h1>
        <p class="text-muted mb-0">Review submitted business information before it becomes publicly verified.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
@endif

<div class="row g-4">
    @forelse($businesses as $business)
        <div class="col-lg-6">
            <article class="card p-4 h-100">
                <div class="d-flex justify-content-between gap-3 mb-3">
                    <div>
                        <h2 class="h5 fw-bold text-dark mb-1">{{ $business->name }}</h2>
                        <p class="small text-muted mb-0">Submitted by {{ $business->user?->email ?: 'Unknown user' }}</p>
                    </div>
                    <span class="badge bg-warning-subtle text-warning-emphasis align-self-start">Pending</span>
                </div>
                <dl class="row small mb-3">
                    <dt class="col-4 text-muted fw-normal">Location</dt>
                    <dd class="col-8 text-dark">{{ $business->locality }}, {{ $business->district_name }}, {{ $business->state }}</dd>
                    <dt class="col-4 text-muted fw-normal">Industry</dt>
                    <dd class="col-8 text-dark">{{ $business->industry_category }}</dd>
                    <dt class="col-4 text-muted fw-normal">Registration</dt>
                    <dd class="col-8 text-dark">{{ $business->registration_number ?: 'Not provided' }}</dd>
                </dl>
                @if($business->description)
                    <p class="small text-muted border-top pt-3">{{ $business->description }}</p>
                @endif
                <div class="d-flex gap-2 mt-auto">
                    <form method="POST" action="{{ route('admin.businesses.status', $business) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="verification_status" value="verified">
                        <button class="btn btn-success btn-sm fw-semibold">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.businesses.status', $business) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="verification_status" value="rejected">
                        <button class="btn btn-outline-danger btn-sm fw-semibold">Reject</button>
                    </form>
                    <a href="{{ route('businesses.show', $business) }}" class="btn btn-outline-secondary btn-sm">View profile</a>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12"><div class="card p-5 text-center"><h2 class="h5 fw-bold text-dark">Queue is clear</h2><p class="text-muted mb-0">There are no pending MSME submissions.</p></div></div>
    @endforelse
</div>

@if($businesses->hasPages())
    <div class="mt-4 d-flex justify-content-center">{{ $businesses->links() }}</div>
@endif
@endsection