<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class AdminBusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::with(['district', 'user'])
            ->where('verification_status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.businesses', compact('businesses'));
    }

    public function updateStatus(Request $request, Business $business)
    {
        $validated = $request->validate([
            'verification_status' => 'required|in:verified,rejected',
        ]);

        $business->update(['verification_status' => $validated['verification_status']]);

        return back()->with('success', 'MSME verification status updated.');
    }
}