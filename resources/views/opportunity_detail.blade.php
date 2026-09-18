@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">&larr; Back to Dashboard</a>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card p-4 shadow-sm bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">{{ $gap->product->name }}</h3>
                    <span class="text-muted">{{ $gap->district->name }}, {{ $gap->district->state }} • HSN: {{ $gap->product->hsn_code }}</span>
                </div>
                <div class="text-end">
                    <span class="badge badge-score fs-5 px-3 py-2">{{ $gap->opportunity_score }} / 100</span>
                    <div class="small text-muted mt-1">Composite Score</div>
                </div>
            </div>

            <hr>

            <h5 class="fw-bold mb-3">Score Factors Breakdown</h5>
            <div class="row text-center mb-3">
                <div class="col"><small class="text-muted d-block">Demand (30%)</small><strong>{{ $gap->score_breakdown['demand_strength'] ?? 0 }}%</strong></div>
                <div class="col"><small class="text-muted d-block">Deficit (25%)</small><strong>{{ $gap->score_breakdown['supply_deficit'] ?? 0 }}%</strong></div>
                <div class="col"><small class="text-muted d-block">Raw Inputs (15%)</small><strong>{{ $gap->score_breakdown['raw_material'] ?? 0 }}%</strong></div>
                <div class="col"><small class="text-muted d-block">Skills (10%)</small><strong>{{ $gap->score_breakdown['skills_availability'] ?? 0 }}%</strong></div>
                <div class="col"><small class="text-muted d-block">Infra (10%)</small><strong>{{ $gap->score_breakdown['infra_readiness'] ?? 0 }}%</strong></div>
                <div class="col"><small class="text-muted d-block">Buyers (10%)</small><strong>{{ $gap->score_breakdown['buyer_presence'] ?? 0 }}%</strong></div>
            </div>

            <canvas id="scoreChart" style="max-height: 220px;"></canvas>
        </div>

        <div class="card p-4 shadow-sm bg-white">
            <h5 class="fw-bold mb-3">Matched Local MSMEs (Capability Alignment)</h5>
            @if($candidateBusinesses->isEmpty())
                <p class="text-muted mb-0">No matching enterprise registered yet with specific machinery for this product.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($candidateBusinesses as $b)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <strong class="text-dark">{{ $b->name }}</strong>
                            <div class="small text-muted">Category: {{ $b->industry_category }}</div>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle">High Match</span>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-4 shadow-sm bg-white mb-4 border-start border-4 border-danger">
            <h5 class="fw-bold text-danger mb-3">Identified Barriers & Prerequisites</h5>
            @if(!empty($gap->barriers))
                @foreach($gap->barriers as $factor => $desc)
                <div class="mb-2">
                    <span class="fw-semibold text-capitalize text-dark">{{ str_replace('_', ' ', $factor) }}:</span>
                    <p class="small text-muted mb-1">{{ $desc }}</p>
                </div>
                @endforeach
            @else
                <p class="text-muted small">No acute bottlenecks logged for this sector.</p>
            @endif
        </div>

        <div class="card p-3 shadow-sm bg-white text-center">
            <h6 class="fw-bold text-dark mb-2">District Action Lead</h6>
            <p class="text-muted small mb-3">Ready to export for the District Industries Centre (DIC) review.</p>
            <a href="{{ route('opportunity.pdf', $gap->id) }}" class="btn btn-success btn-sm w-100">
    Download Official PDF Brief
</a>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('scoreChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Demand (30%)', 'Deficit (25%)', 'Raw Material (15%)', 'Skills (10%)', 'Infra (10%)', 'Buyers (10%)'],
            datasets: [{
                label: 'Normalized Factor Score (0-100)',
                data: [
                    {{ $gap->score_breakdown['demand_strength'] ?? 0 }},
                    {{ $gap->score_breakdown['supply_deficit'] ?? 0 }},
                    {{ $gap->score_breakdown['raw_material'] ?? 0 }},
                    {{ $gap->score_breakdown['skills_availability'] ?? 0 }},
                    {{ $gap->score_breakdown['infra_readiness'] ?? 0 }},
                    {{ $gap->score_breakdown['buyer_presence'] ?? 0 }}
                ],
                backgroundColor: '#059669',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, max: 100 } }
        }
    });
</script>
@endsection