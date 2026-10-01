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
                    <label class="form-label fw-semibold">Target Product</label>
                    <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required {{ $products->isEmpty() ? 'disabled' : '' }}>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->category }} - {{ $product->unit }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($products->isEmpty())
                        <div class="form-text text-warning">No products are configured yet. Ask an administrator to load the reference data.</div>
                    @endif
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

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-bold py-2">
                        Update Supply Baseline & Recompute Deficit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
