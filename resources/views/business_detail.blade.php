@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <a href="{{ route('businesses') }}" class="btn btn-sm btn-outline-secondary mb-3">&larr; Back to directory</a>
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-success-subtle text-success border border-success-subtle">{{ ucfirst($business->verification_status) }}</span>
            <span class="small text-muted">MSME profile</span>
        </div>
        <h1 class="h2 fw-bold text-dark mb-1">{{ $business->name }}</h1>
        <p class="text-muted mb-0">{{ $business->industry_category }}</p>
    </div>
    @if($business->registration_number)
        <div class="text-md-end">
            <div class="small text-muted">Udyam / MSME registration</div>
            <strong class="text-dark">{{ $business->registration_number }}</strong>
        </div>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card p-4 p-lg-5 h-100">
            <h2 class="h5 fw-bold text-dark mb-4">Enterprise overview</h2>
            <dl class="row mb-0">
                <dt class="col-sm-4 text-muted fw-normal mb-3">State</dt>
                <dd class="col-sm-8 text-dark mb-3">{{ $business->state ?: $business->district?->state ?: 'Not provided' }}</dd>
                <dt class="col-sm-4 text-muted fw-normal mb-3">District</dt>
                <dd class="col-sm-8 text-dark mb-3">{{ $business->district_name ?: $business->district?->name ?: 'Not provided' }}</dd>
                <dt class="col-sm-4 text-muted fw-normal mb-3">Locality</dt>
                <dd class="col-sm-8 text-dark mb-3">{{ $business->locality ?: 'Not provided' }}</dd>
                <dt class="col-sm-4 text-muted fw-normal">Capability</dt>
                <dd class="col-sm-8 text-dark">{{ $business->industry_category }}</dd>
            </dl>
            @if($business->description)
                <div class="border-top pt-4 mt-4">
                    <h3 class="h6 fw-bold text-dark">About this enterprise</h3>
                    <p class="text-muted mb-0">{{ $business->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card p-4 mb-4">
            <h2 class="h5 fw-bold text-dark mb-3">Connect with this MSME</h2>
            <p class="small text-muted">Use the registered contact to discuss sourcing, partnerships, or local capacity.</p>
            <div class="d-grid gap-2">
                <a href="tel:{{ $business->phone }}" class="btn btn-success">Call {{ $business->phone }}</a>
                @if($business->email)
                    <a href="mailto:{{ $business->email }}" class="btn btn-outline-primary">Email enterprise</a>
                @endif
            </div>
        </div>

        @if($business->latitude !== null && $business->longitude !== null)
            <div class="card p-3">
                <h2 class="h6 fw-bold text-dark mb-3">Precise operating location</h2>
                <div id="business-detail-map" class="rounded border" style="height: 240px;" data-latitude="{{ $business->latitude }}" data-longitude="{{ $business->longitude }}"></div>
                <a class="btn btn-sm btn-outline-primary mt-3" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $business->latitude }}&mlon={{ $business->longitude }}#map=17/{{ $business->latitude }}/{{ $business->longitude }}">Open in OpenStreetMap</a>
            </div>
        @endif
    </div>
</div>

@if($business->latitude !== null && $business->longitude !== null)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapElement = document.getElementById('business-detail-map');
        const latitude = Number(mapElement.dataset.latitude);
        const longitude = Number(mapElement.dataset.longitude);
        const map = L.map(mapElement).setView([latitude, longitude], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([latitude, longitude]).addTo(map);
    });
</script>
@endif
@endsection