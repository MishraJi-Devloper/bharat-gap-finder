@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0 text-dark">Ingest Market Demand Signal</h4>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">← Back to Dashboard</a>
        </div>

        <div class="card shadow-sm bg-white p-4">
            <form action="{{ route('demand.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Target District</label>
                    <select name="district_id" class="form-select @error('district_id') is-invalid @enderror" required>
                        <option value="">Select District</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }} ({{ $district->state }})</option>
                        @endforeach
                    </select>
                    @error('district_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Target Product</label>
                    <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->category }} - {{ $product->unit }})</option>
                        @endforeach
                    </select>
                    @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Annual Demand Quantity</label>
                        <input type="number" step="0.01" name="quantity" class="form-control @error('quantity') is-invalid @enderror" placeholder="e.g. 750000" required>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Time Period</label>
                        <input type="text" name="period" class="form-control" value="2026-Annual" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Data Source / Reference</label>
                    <input type="text" name="source" class="form-control" placeholder="e.g. DIC Industrial Survey / MSME Cluster RFP">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success fw-bold py-2">
                        Calculate Gap & Update Intelligence Pipeline
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
