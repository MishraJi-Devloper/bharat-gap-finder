<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Business;
use App\Models\ManufacturingGap;
use App\Models\Product;
use App\Models\MarketDemand;
use App\Models\LocalProduction;
use App\Services\GapCalculationService;
use App\Services\OpportunityScoringService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // 10 minutes cache: Bar-bar remote database (Supabase) ping delay 0 ho jayega
        $dashboardData = Cache::remember('bmgf_dashboard_payload', 600, function () {
            // 1. Fetch gaps with relations in 1 single query
            $gaps = ManufacturingGap::with(['district', 'product'])
                ->orderBy('opportunity_score', 'desc')
                ->get();

            // 2. Single trip query with counts (eliminates N+1 loop roundtrips)
            $districts = District::withCount(['businesses', 'manufacturingGaps'])->get();

            // 3. In-memory calculations (0 extra DB queries)
            $totalDistricts = $districts->count();
            $totalBusinesses = Business::count();
            $totalGaps = $gaps->where('status', 'published')->count();

            // 4. Map markers payload formatting
            $mapDistricts = $districts->map(function ($district) {
                return [
                    'name' => $district->name,
                    'state' => $district->state,
                    'lat' => (float) $district->latitude,
                    'lng' => (float) $district->longitude,
                    'businesses_count' => $district->businesses_count,
                    'gaps_count' => $district->manufacturing_gaps_count,
                ];
            });

            return [
                'totalDistricts' => $totalDistricts,
                'totalBusinesses' => $totalBusinesses,
                'totalGaps' => $totalGaps,
                'gaps' => $gaps,
                'mapDistricts' => $mapDistricts,
                'mapDistrictsJson' => json_encode($mapDistricts),
            ];
        });

        return view('dashboard', $dashboardData);
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

    public function createDemand()
    {
        $districts = District::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('create_demand', compact('districts', 'products'));
    }

    public function storeDemand(Request $request, GapCalculationService $gapService, OpportunityScoringService $scoreService)
    {
        $validated = $request->validate([
            'district_id' => 'required|uuid|exists:districts,id',
            'product_id' => 'required|uuid|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'period' => 'required|string',
            'source' => 'nullable|string|max:255',
        ]);

        // Market demand record ya update karein
        MarketDemand::updateOrCreate(
            [
                'district_id' => $validated['district_id'],
                'product_id' => $validated['product_id'],
                'period' => $validated['period'],
            ],
            [
                'quantity' => $validated['quantity'],
                'source' => $validated['source'] ?? 'Direct Platform Ingestion',
                'verification_status' => 'verified',
            ]
        );

        // Instant automatic gap identification & scoring computation
        $gap = $gapService->calculateForProductAndDistrict(
            $validated['district_id'],
            $validated['product_id'],
            $validated['period']
        );

        if ($gap) {
            $scoreService->computeScore($gap);
        }

        // Cache clear taaki naya record turant dashboard par show ho
        Cache::forget('bmgf_dashboard_payload');

        return redirect()->route('dashboard')->with('success', 'Market Demand successfully recorded and Opportunity Score recomputed!');
    }

    public function createSupply()
    {
        $districts = District::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $businesses = Business::orderBy('name')->get();

        return view('create_supply', compact('districts', 'products', 'businesses'));
    }

    public function storeSupply(Request $request, GapCalculationService $gapService, OpportunityScoringService $scoreService)
    {
        $validated = $request->validate([
            'district_id' => 'required|uuid|exists:districts,id',
            'product_id' => 'required|uuid|exists:products,id',
            'business_id' => 'nullable|uuid|exists:businesses,id',
            'installed_capacity' => 'required|numeric|min:0',
            'actual_production' => 'required|numeric|min:0',
            'period' => 'required|string',
        ]);

        LocalProduction::updateOrCreate(
            [
                'district_id' => $validated['district_id'],
                'product_id' => $validated['product_id'],
                'business_id' => $validated['business_id'] ?? null,
                'period' => $validated['period'],
            ],
            [
                'installed_capacity' => $validated['installed_capacity'],
                'actual_production' => $validated['actual_production'],
            ]
        );

        // Recompute the deficit & scoring dynamically
        $gap = $gapService->calculateForProductAndDistrict(
            $validated['district_id'],
            $validated['product_id'],
            $validated['period']
        );

        if ($gap) {
            $scoreService->computeScore($gap);
        }

        // Cache clear taaki updated data instantly dashboard par show ho
        Cache::forget('bmgf_dashboard_payload');

        return redirect()->route('dashboard')->with('success', 'Local Production Capacity recorded & Deficit updated!');
    }

    public function compare(Request $request)
    {
        $districts = District::orderBy('name')->get();

        $districtA_id = $request->query('district_a', $districts->first()?->id);
        $districtB_id = $request->query('district_b', $districts->skip(1)->first()?->id);

        $districtA = District::with(['businesses', 'manufacturingGaps.product'])->find($districtA_id);
        $districtB = District::with(['businesses', 'manufacturingGaps.product'])->find($districtB_id);

        return view('compare', compact('districts', 'districtA', 'districtB'));
    }
}