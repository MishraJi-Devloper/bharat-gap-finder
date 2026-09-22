@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">&larr; Back to Dashboard</a>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <!-- Main Opportunity Header Card -->
        <div class="card p-4 shadow-sm bg-white mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">{{ $gap->product->name }}</h3>
                    <span class="text-muted">{{ $gap->district->name }}, {{ $gap->district->state }} &bull; HSN: {{ $gap->product->hsn_code ?? 'N/A' }}</span>
                </div>
                <div class="text-end">
                    <span class="badge badge-score fs-5 px-3 py-2">{{ $gap->opportunity_score }} / 100</span>
                    <div class="small text-muted mt-1">Composite Score</div>
                </div>
            </div>

            <hr>

            <!-- Factors Summary Numbers -->
            <h6 class="fw-bold text-uppercase small text-muted mb-3">Multi-Factor Weightage & Ratings</h6>
            <div class="row text-center g-2 mb-4">
                <div class="col-4 col-md-2">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Demand (30%)</small>
                        <strong class="text-dark">{{ $gap->score_breakdown['demand_strength'] ?? 0 }}%</strong>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Deficit (25%)</small>
                        <strong class="text-dark">{{ $gap->score_breakdown['supply_deficit'] ?? 0 }}%</strong>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Raw Mat (15%)</small>
                        <strong class="text-dark">{{ $gap->score_breakdown['raw_material'] ?? 0 }}%</strong>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Skills (10%)</small>
                        <strong class="text-dark">{{ $gap->score_breakdown['skills_readiness'] ?? ($gap->score_breakdown['skills_availability'] ?? 0) }}%</strong>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Infra (10%)</small>
                        <strong class="text-dark">{{ $gap->score_breakdown['infra_readiness'] ?? 0 }}%</strong>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="p-2 border rounded bg-light">
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Buyers (10%)</small>
                        <strong class="text-dark">{{ $gap->score_breakdown['anchor_buyers'] ?? ($gap->score_breakdown['buyer_presence'] ?? 0) }}%</strong>
                    </div>
                </div>
            </div>

            <!-- Dual Visual Factor Analytics (Bar & Radar) -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">Factor Score Distribution</h6>
                        <p class="text-muted small mb-2">Normalized factor scores (0-100 scale)</p>
                        <div style="height: 250px;">
                            <canvas id="factorBarChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">District Viability Profile</h6>
                        <p class="text-muted small mb-2">Multivariate sensitivity radar polygon</p>
                        <div style="height: 250px;">
                            <canvas id="factorRadarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matched MSMEs List -->
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

    <!-- Right Sidebar -->
    <div class="col-md-4">
        <div class="card p-4 shadow-sm bg-white mb-4 border-top-0 border-end-0 border-bottom-0 border-start border-4 border-danger">
            <h5 class="fw-bold text-danger mb-3">Identified Barriers & Prerequisites</h5>
            @if(!empty($gap->barriers))
                @foreach($gap->barriers as $factor => $desc)
                <div class="mb-3">
                    <span class="fw-semibold text-capitalize text-dark d-block">{{ str_replace('_', ' ', $factor) }}:</span>
                    <p class="small text-muted mb-0">{{ $desc }}</p>
                </div>
                @endforeach
            @else
                <p class="text-muted small mb-0">No acute bottlenecks logged for this sector.</p>
            @endif
        </div>

        <div class="card p-3 shadow-sm bg-white text-center">
            <h6 class="fw-bold text-dark mb-2">District Action Lead</h6>
            <p class="text-muted small mb-3">Ready to export for District Industries Centre (DIC) review.</p>
            <a href="{{ route('opportunity.pdf', $gap->id) }}" class="btn btn-success btn-sm w-100 fw-semibold">
                Download Official PDF Brief
            </a>
        </div>
    </div>
</div>

<!-- Chart.js Dual Analytics Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const factorLabels = ['Demand (30%)', 'Deficit (25%)', 'Raw Mat (15%)', 'Skills (10%)', 'Infra (10%)', 'Buyers (10%)'];
        
        const factorValues = [
            {{ (float)($gap->score_breakdown['demand_strength'] ?? 0) }},
            {{ (float)($gap->score_breakdown['supply_deficit'] ?? 0) }},
            {{ (float)($gap->score_breakdown['raw_material'] ?? 0) }},
            {{ (float)($gap->score_breakdown['skills_readiness'] ?? ($gap->score_breakdown['skills_availability'] ?? 0)) }},
            {{ (float)($gap->score_breakdown['infra_readiness'] ?? 0) }},
            {{ (float)($gap->score_breakdown['anchor_buyers'] ?? ($gap->score_breakdown['buyer_presence'] ?? 0)) }}
        ];

        // 1. Horizontal Bar Chart
        const barCtx = document.getElementById('factorBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: factorLabels,
                datasets: [{
                    label: 'Factor Score',
                    data: factorValues,
                    backgroundColor: 'rgba(5, 150, 105, 0.85)',
                    borderColor: '#059669',
                    borderRadius: 4,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' } },
                    y: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // 2. Multi-Factor Radar Chart
        const radarCtx = document.getElementById('factorRadarChart').getContext('2d');
        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: ['Demand', 'Deficit', 'Raw Mat', 'Skills', 'Infra', 'Buyers'],
                datasets: [{
                    label: 'Readiness Profile',
                    data: factorValues,
                    fill: true,
                    backgroundColor: 'rgba(37, 99, 235, 0.2)',
                    borderColor: '#2563eb',
                    pointBackgroundColor: '#1d4ed8',
                    pointBorderColor: '#ffffff',
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#1d4ed8'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: '#e2e8f0' },
                        grid: { color: '#e2e8f0' },
                        suggestedMin: 0,
                        suggestedMax: 100,
                        ticks: { stepSize: 25, display: false },
                        pointLabels: {
                            font: { size: 10, weight: '600' },
                            color: '#475569'
                        }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endsection