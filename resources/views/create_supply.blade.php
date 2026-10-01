@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark">Log Local Production Capacity (Supply Side)</h4>
                <p class="text-muted small mb-0">Record existing enterprise capacity to balance the deficit engine</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">&larr; Back to Dashboard</a>
        </div>

        <div class="card shadow-sm bg-white p-4">
            <form action="{{ route('supply.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="state" class="form-label fw-semibold">State / Union Territory</label>
                        <input id="state" name="state" value="{{ old('state') }}" class="form-control @error('state') is-invalid @enderror" placeholder="e.g. Punjab" required>
                        @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="district_name" class="form-label fw-semibold">District</label>
                        <input id="district_name" name="district_name" value="{{ old('district_name') }}" class="form-control @error('district_name') is-invalid @enderror" placeholder="e.g. Ludhiana" required>
                        @error('district_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                        <label for="product_name" class="form-label fw-semibold">Target product</label>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <input id="product_name" name="product_name" value="{{ old('product_name') }}" class="form-control @error('product_name') is-invalid @enderror" placeholder="e.g. Solar mounting brackets" required>
                                @error('product_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="product_category" class="form-label fw-semibold">Category</label>
                                <input id="product_category" name="product_category" value="{{ old('product_category') }}" class="form-control @error('product_category') is-invalid @enderror" placeholder="e.g. Electrical" required>
                                @error('product_category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="product_unit" class="form-label fw-semibold">Unit</label>
                                <input id="product_unit" name="product_unit" value="{{ old('product_unit') }}" class="form-control @error('product_unit') is-invalid @enderror" placeholder="e.g. Units" required>
                                @error('product_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">MSME Unit / Enterprise (Optional)</label>
                    <select name="business_id" class="form-select">
                        <option value="">Aggregate / Unassigned Unit</option>
                        @foreach($businesses as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->industry_category }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Installed Annual Capacity</label>
                        <input type="number" step="0.01" name="installed_capacity" class="form-control" placeholder="e.g. 500000" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Actual Production</label>
                        <input type="number" step="0.01" name="actual_production" class="form-control" placeholder="e.g. 320000" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Time Period</label>
                    <input type="text" name="period" class="form-control" value="2026-Annual" required>
                </div>

                <div class="border rounded p-3 mb-4 bg-light">
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                        <div>
                            <label class="form-label fw-semibold mb-1">Precise district location</label>
                            <div class="form-text mt-0">Add coordinates so this district appears on the dashboard map.</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success use-location">Use my location</button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6"><input name="latitude" class="form-control" inputmode="decimal" placeholder="Latitude e.g. 30.9010"></div>
                        <div class="col-md-6"><input name="longitude" class="form-control" inputmode="decimal" placeholder="Longitude e.g. 75.8573"></div>
                    </div>
                    <div class="location-status small text-muted mt-2" role="status"></div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-bold py-2">
                        Update Supply Baseline & Recompute Deficit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.querySelector('.use-location')?.addEventListener('click', function () {
        const status = document.querySelector('.location-status');
        if (!navigator.geolocation) { status.textContent = 'Location is not supported by this browser.'; return; }
        status.textContent = 'Requesting your location...';
        navigator.geolocation.getCurrentPosition(function (position) {
            document.querySelector('[name="latitude"]').value = position.coords.latitude.toFixed(7);
            document.querySelector('[name="longitude"]').value = position.coords.longitude.toFixed(7);
            status.textContent = 'Location added. The dashboard can now plot this district.';
        }, function () { status.textContent = 'Location unavailable. Enter coordinates manually.'; });
    });
</script>
@endsection
