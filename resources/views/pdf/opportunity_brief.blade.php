<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Opportunity Brief</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; color: #1e293b; }
        .header { border-bottom: 2px solid #059669; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 18pt; font-weight: bold; color: #0f172a; margin: 0; }
        .meta { color: #64748b; font-size: 9pt; }
        .score-box { background: #ecfdf5; border: 1px solid #a7f3d0; padding: 10px; border-radius: 4px; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; font-size: 9.5pt; }
        th { background-color: #f1f5f9; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Bharat Manufacturing Gap Finder</div>
        <div class="meta">District Opportunity Brief • {{ $gap->district->name }}, {{ $gap->district->state }}</div>
    </div>

    <h3>Product: {{ $gap->product->name }} (HSN: {{ $gap->product->hsn_code }})</h3>
    <p>Estimated Supply Deficit: <strong>{{ number_format($gap->estimated_gap) }} {{ $gap->product->unit }}</strong></p>

    <div class="score-box">
        <strong>Composite Opportunity Score: {{ $gap->opportunity_score }} / 100</strong>
        <p style="margin: 5px 0 0 0; font-size: 8.5pt;">Weighted by Demand (30%), Deficit (25%), Raw Materials (15%), Skills (10%), Infra (10%), Buyers (10%).</p>
    </div>

    <h4>Local MSME Matching Candidates</h4>
    <table>
        <thead>
            <tr>
                <th>Enterprise Name</th>
                <th>Category</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($candidateBusinesses as $b)
            <tr>
                <td>{{ $b->name }}</td>
                <td>{{ $b->industry_category }}</td>
                <td>Verified Match</td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No candidate enterprises found in this district cluster.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>