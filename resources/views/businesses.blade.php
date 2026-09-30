@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
    <div>
        <span class="badge bg-success-subtle text-success border border-success-subtle mb-2">Local manufacturing network</span>
        <h1 class="h3 fw-bold text-dark mb-1">MSME Directory</h1>
        <p class="text-muted mb-0">Discover registered enterprises, capabilities, and local operating areas.</p>
    </div>
    <a href="{{ route('businesses.create') }}" class="btn btn-success fw-semibold">Register your MSME</a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm" role="alert">{{ session('success') }}</div>
@endif

<div class="card p-3 p-lg-4 mb-4">
    <form method="GET" action="{{ route('businesses') }}" class="row g-2 align-items-end">
        <div class="col-lg-10">
            <label for="business-search" class="form-label small fw-semibold text-muted">Search by enterprise, locality, or industry</label>
            <input id="business-search" type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="e.g. Punjab, Howrah, packaging, precision engineering">
        </div>
        <div class="col-lg-2 d-grid">
            <button type="submit" class="btn btn-primary fw-semibold">Search directory</button>
        </div>
    </form>
</div>

<div class="row g-4">
    @forelse($businesses as $business)
        <div class="col-md-6 col-xl-4">
            <article class="card h-100 p-4">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <h2 class="h5 fw-bold text-dark mb-1">{{ $business->name }}</h2>
                        <p class="small text-muted mb-0">{{ $business->industry_category }}</p>
                    </div>
                    <span class="badge {{ $business->verification_status === 'verified' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }}">
                        {{ ucfirst($business->verification_status) }}
                    </span>
                </div>
                <dl class="row small mb-0">
                    <dt class="col-5 text-muted fw-normal">Operating area</dt>
                    <dd class="col-7 text-dark">{{ $business->locality }}, {{ $business->district_name ?: $business->district?->name }}, {{ $business->state ?: $business->district?->state }}</dd>
                    @if($business->registration_number)
                        <dt class="col-5 text-muted fw-normal">Registration</dt>
                        <dd class="col-7 text-dark">{{ $business->registration_number }}</dd>
                    @endif
                    <dt class="col-5 text-muted fw-normal">Contact</dt>
                    <dd class="col-7 text-dark mb-0">{{ $business->contact_name }}</dd>
                </dl>
                @if($business->latitude !== null && $business->longitude !== null)
                    <a class="btn btn-sm btn-outline-primary mt-3" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $business->latitude }}&mlon={{ $business->longitude }}#map=17/{{ $business->latitude }}/{{ $business->longitude }}">
                        View precise map location
                    </a>
                @endif
                <a href="{{ route('businesses.show', $business) }}" class="btn btn-sm btn-outline-secondary mt-3">View profile</a>
                @if($business->description)
                    <p class="small text-muted border-top pt-3 mt-3 mb-0">{{ $business->description }}</p>
                @endif
            </article>
        </div>
    @empty
        <div class="col-12">
            <div class="card p-5 text-center">
                <h2 class="h5 fw-bold text-dark">No MSMEs found</h2>
                <p class="text-muted mb-3">Try another search or add the first enterprise in this directory.</p>
                <div><a href="{{ route('businesses.create') }}" class="btn btn-success fw-semibold">Register an MSME</a></div>
            </div>
        </div>
    @endforelse
</div>

@if($businesses->hasPages())
    <div class="mt-4 d-flex justify-content-center">{{ $businesses->links() }}</div>
@endif
@endsection
