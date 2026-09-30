<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminInvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::latest()->paginate(20);

        return view('admin.invitations', compact('invitations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email|max:255']);
        $invitation = Invitation::create([
            'invited_by' => $request->user()->id,
            'email' => $validated['email'],
            'role' => 'member',
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        return back()->with('invite_url', route('register', ['invite' => $invitation->token]));
    }
}