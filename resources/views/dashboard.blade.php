@extends('layouts.app')

@section('content')
<!-- Metric Summary Cards -->
<div class="row mb-4 g-3">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm bg-white border-top-0 border-end-0 border-bottom-0 border-start border-4 border-primary">
            <h6 class="text-muted text-uppercase small fw-semibold">Pilot Districts Covered</h6>
            <h2 class="fw-bold mb-0 text-dark">{{ $totalDistricts }}</h2>
            <small class="text-muted">West Bengal Industrial Clusters</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 shadow-sm bg-white border-top-0 border-end-0 border-bottom-0 border-start border-4 border-info">
            <h6 class="text-muted text-uppercase small fw-semibold">Verified Local MSMEs</h6>
            <h2 class="fw-bold mb-0 text-dark">{{ $totalBusinesses }}</h2>
            <small class="text-muted">Equipped for rapid turnaround</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 shadow-sm bg-white border-top-0 border-end-0 border-bottom-0 border-start border-4 border-danger">
            <h6 class="text-muted text-uppercase small fw-semibold">Active Supply Gaps Detected</h6>
            <h2 class="fw-bold mb-0 text-danger">{{ $totalGaps }}</h2>
            <small class="text-muted">Unmet industrial procurement demand</small>
        </div>
    </div>
</div>

<!-- Interactive District Spatial Intelligence Map -->
<div class="card shadow-sm bg-white mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0 text-dark">District Industrial Intelligence Map</h5>
            <p class="text-muted small mb-0">Geospatial visualization of target districts, critical deficit points, and industrial clusters</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Red: Active Gap</span>
            <span class="badge bg-success-subtle text-success border border-success-subtle">Green: Saturated</span>
        </div>
    </div>
    <div class="card-body p-0">
        <div id="districtMap" style="height: 400px; width: 100%; border-radius: 0 0 8px 8px; z-index: 1;"></div>
    </div>
</div>

<!-- Identified Manufacturing Gaps Table with Live Filter Controls -->
<div class="card shadow-sm bg-white mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <div class="row align-items-center g-2">
            <div class="col-md-6">
                <h5 class="fw-bold mb-0 text-dark">Ranked Manufacturing Gaps & Opportunity Pipeline</h5>
                <p class="text-muted small mb-0">Sorted by multi-factor score: 0.30D + 0.25S + 0.15R + 0.10K + 0.10I + 0.10B</p>
            </div>
            <div class="col-md-6 d-flex gap-2 justify-content-md-end">
                <input type="text" id="tableSearch" class="form-control form-control-sm w-50" placeholder="Filter by product or district...">
                <select id="categoryFilter" class="form-select form-select-sm w-50">
                    <option value="">All Categories</option>
                    @foreach($gaps->pluck('product.category')->unique() as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="gapsTable">
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
                @forelse($gaps as $gap)
                <tr>
                    <td class="ps-4 fw-bold text-dark product-name">{{ $gap->product->name }}</td>
                    <td class="district-name">{{ $gap->district->name }} <span class="text-muted small">({{ $gap->district->state }})</span></td>
                    <td><span class="badge bg-secondary category-badge">{{ $gap->product->category }}</span></td>
                    <td class="fw-bold text-danger">{{ number_format($gap->estimated_gap) }} {{ $gap->product->unit }}</td>
                    <td>
                        <span class="badge badge-score px-3 py-1">{{ $gap->opportunity_score }} / 100</span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('opportunity.show', $gap->id) }}" class="btn btn-outline-success fw-semibold">
                                View Deep Analysis
                            </a>
                            <a href="{{ route('opportunity.pdf', $gap->id) }}" class="btn btn-outline-secondary" title="Export PDF">
                                PDF
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        No active manufacturing gaps identified yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Integrated Spatial & Filter Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('districtMap').setView([22.3, 87.9], 8);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Raw output without Blade directive decorators
        const districtsData = '{!! addslashes($mapDistrictsJson ?? "[]") !!}';
        const districts = JSON.parse(districtsData);
        const markerGroup = [];

        districts.forEach(function (d) {
            if (d.lat && d.lng) {
                const marker = L.circleMarker([d.lat, d.lng], {
                    radius: 11,
                    fillColor: d.gaps_count > 0 ? '#dc2626' : '#059669',
                    color: '#ffffff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.85
                }).addTo(map);

                marker.bindPopup(
                    '<div style="font-family: sans-serif; font-size: 13px; line-height: 1.4;">' +
                    '<strong style="font-size: 14px;">' + d.name + '</strong> (' + d.state + ')<br>' +
                    '<hr style="margin: 6px 0;">' +
                    '<span style="color: #dc2626; font-weight: 700;">Active Deficits: ' + d.gaps_count + '</span><br>' +
                    '<span style="color: #475569;">Verified MSMEs: ' + d.businesses_count + '</span>' +
                    '</div>'
                );

                markerGroup.push([d.lat, d.lng]);
            }
        });

        if (markerGroup.length > 0) {
            map.fitBounds(markerGroup, { padding: [50, 50] });
        }

        const searchInput = document.getElementById('tableSearch');
        const categoryFilter = document.getElementById('categoryFilter');
        const rows = document.querySelectorAll('#gapsTable tbody tr');

        function filterTable() {
            const query = searchInput.value.toLowerCase().trim();
            const selectedCat = categoryFilter.value.toLowerCase().trim();

            rows.forEach(function (row) {
                const productName = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
                const districtName = row.querySelector('.district-name')?.textContent.toLowerCase() || '';
                const category = row.querySelector('.category-badge')?.textContent.toLowerCase() || '';

                const matchesQuery = productName.includes(query) || districtName.includes(query);
                const matchesCategory = !selectedCat || category === selectedCat;

                row.style.display = (matchesQuery && matchesCategory) ? '' : 'none';
            });
        }

        searchInput.addEventListener('keyup', filterTable);
        categoryFilter.addEventListener('change', filterTable);
    });
</script>
@endsection