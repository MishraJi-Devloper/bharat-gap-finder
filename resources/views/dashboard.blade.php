@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm bg-white">
            <h6 class="text-muted text-uppercase small">Pilot Districts</h6>
            <h3 class="fw-bold mb-0 text-dark">{{ $totalDistricts }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 shadow-sm bg-white">
            <h6 class="text-muted text-uppercase small">Verified Local MSMEs</h6>
            <h3 class="fw-bold mb-0 text-dark">{{ $totalBusinesses }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 shadow-sm bg-white">
            <h6 class="text-muted text-uppercase small">Active Supply Gaps Detected</h6>
            <h3 class="fw-bold mb-0 text-success">{{ $totalGaps }}</h3>
        </div>
    </div>
</div>

<div class="card shadow-sm bg-white mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="fw-bold mb-0 text-dark">Identified Local Manufacturing Opportunities</h5>
        <p class="text-muted small mb-0">Ranked by composite multi-factor scoring (Demand, Supply Deficit, Skills, Raw Materials)</p>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Product Name</th>
                    <th>District</th>
                    <th>Category</th>
                    <th>Est. Local Deficit</th>
                    <th>Opportunity Score</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gaps as $gap)
                <tr>
                    <td class="ps-4 fw-bold text-dark">{{ $gap->product->name }}</td>
                    <td>{{ $gap->district->name }} ({{ $gap->district->state }})</td>
                    <td><span class="badge bg-secondary">{{ $gap->product->category }}</span></td>
                    <td class="fw-bold text-danger">{{ number_format($gap->estimated_gap) }} {{ $gap->product->unit }}</td>
                    <td>
                        <span class="badge badge-score px-3 py-1">{{ $gap->opportunity_score }} / 100</span>
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('opportunity.show', $gap->id) }}" class="btn btn-sm btn-outline-success fw-semibold">
                            View Deep Analysis
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection