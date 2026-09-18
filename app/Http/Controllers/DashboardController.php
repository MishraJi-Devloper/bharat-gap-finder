<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Business;
use App\Models\ManufacturingGap;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDistricts = District::count();
        $totalBusinesses = Business::count();
        $totalGaps = ManufacturingGap::where('status', 'published')->count();
        $gaps = ManufacturingGap::with(['district', 'product'])->orderBy('opportunity_score', 'desc')->get();

        return view('dashboard', compact('totalDistricts', 'totalBusinesses', 'totalGaps', 'gaps'));
    }

    public function show(string $id)
    {
        $gap = ManufacturingGap::with(['district', 'product'])->findOrFail($id);
        
        // Find candidate MSMEs in the same district or same category
        $candidateBusinesses = Business::where('district_id', $gap->district_id)
            ->where('industry_category', $gap->product->category)
            ->get();

        return view('opportunity_detail', compact('gap', 'candidateBusinesses'));
    }
    public function exportPdf(string $id)
{
    $gap = ManufacturingGap::with(['district', 'product'])->findOrFail($id);
    $candidateBusinesses = Business::where('district_id', $gap->district_id)
        ->where('industry_category', $gap->product->category)
        ->get();

    $pdf = Pdf::loadView('pdf.opportunity_brief', compact('gap', 'candidateBusinesses'));
    return $pdf->download("Opportunity_Brief_{$gap->product->name}.pdf");
}
}