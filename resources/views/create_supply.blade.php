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

                <div class="mb-3">
                    <label class="form-label fw-semibold">Target District</label>
                    <select name="district_id" class="form-select @error('district_id') is-invalid @enderror" required>
                        <option value="">Select District</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                {{ $district->name }} ({{ $district->state }})
                            </option>
                        @endforeach
                    </select>
                    @error('district_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Target Product</label>
                    <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->category }} - {{ $product->unit }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
