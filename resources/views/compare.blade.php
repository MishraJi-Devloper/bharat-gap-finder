@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark">District Industrial Benchmark Comparison</h4>
        <p class="text-muted small mb-0">Side-by-side capacity, deficit, and infrastructure comparison</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">&larr; Back to Dashboard</a>
</div>

<!-- District Selectors Form -->
<form method="GET" action="{{ route('compare') }}" class="card p-3 shadow-sm bg-white mb-4 border-0">
    <div class="row g-3 align-items-center">
        <div class="col-md-5">
            <label class="form-label small fw-semibold text-muted">Primary District (A)</label>
            <select name="district_a" class="form-select" onchange="this.form.submit()">
                @foreach($districts as $d)
                    <option value="{{ $d->id }}" {{ $districtA?->id == $d->id ? 'selected' : '' }}>{{ $d->name }} ({{ $d->state }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 text-center pt-md-4">
            <span class="badge bg-secondary px-3 py-2">VS</span>
        </div>
        <div class="col-md-5">
            <label class="form-label small fw-semibold text-muted">Benchmark District (B)</label>
            <select name="district_b" class="form-select" onchange="this.form.submit()">
                @foreach($districts as $d)
                    <option value="{{ $d->id }}" {{ $districtB?->id == $d->id ? 'selected' : '' }}>{{ $d->name }} ({{ $d->state }})</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

<!-- Comparison Grid -->
<div class="row g-4">
    <!-- District A Column -->
    <div class="col-md-6">
        <div class="card p-4 shadow-sm bg-white border-top-0 border-end-0 border-bottom-0 border-start border-4 border-primary h-100">
            <h4 class="fw-bold text-dark mb-1">{{ $districtA?->name }}</h4>
            <span class="text-muted small d-block mb-3">{{ $districtA?->state }} Corridor</span>

            <div class="row g-2 text-center mb-4">
                <div class="col-6">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Verified MSMEs</small>
                        <h4 class="fw-bold text-dark mb-0">{{ $districtA?->businesses->count() }}</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Active Supply Gaps</small>
                        <h4 class="fw-bold text-danger mb-0">{{ $districtA?->manufacturingGaps->count() }}</h4>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Identified Gaps & Opportunities</h6>
            <div class="list-group list-group-flush border rounded">
                @forelse($districtA?->manufacturingGaps ?? [] as $gap)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="text-dark">{{ $gap->product->name }}</strong>
                        <small class="text-muted d-block">Deficit: {{ number_format($gap->estimated_gap) }} {{ $gap->product->unit }}</small>
                    </div>
                    <span class="badge bg-success px-2 py-1">{{ $gap->opportunity_score }}/100</span>
                </div>
                @empty
                <div class="list-group-item text-center text-muted small py-3">No gaps registered</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- District B Column -->
    <div class="col-md-6">
        <div class="card p-4 shadow-sm bg-white border-top-0 border-end-0 border-bottom-0 border-start border-4 border-info h-100">
            <h4 class="fw-bold text-dark mb-1">{{ $districtB?->name }}</h4>
            <span class="text-muted small d-block mb-3">{{ $districtB?->state }} Corridor</span>

            <div class="row g-2 text-center mb-4">
                <div class="col-6">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Verified MSMEs</small>
                        <h4 class="fw-bold text-dark mb-0">{{ $districtB?->businesses->count() }}</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Active Supply Gaps</small>
                        <h4 class="fw-bold text-danger mb-0">{{ $districtB?->manufacturingGaps->count() }}</h4>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Identified Gaps & Opportunities</h6>
            <div class="list-group list-group-flush border rounded">
                @forelse($districtB?->manufacturingGaps ?? [] as $gap)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong class="text-dark">{{ $gap->product->name }}</strong>
                        <small class="text-muted d-block">Deficit: {{ number_format($gap->estimated_gap) }} {{ $gap->product->unit }}</small>
                    </div>
                    <span class="badge bg-success px-2 py-1">{{ $gap->opportunity_score }}/100</span>
                </div>
                @empty
                <div class="list-group-item text-center text-muted small py-3">No gaps registered</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
