<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->intended(route('businesses'));
    }

    public function showRegister()
    {
        $invitation = Invitation::where('token', request('invite'))
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();

        return view('auth.register', compact('invitation'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'invite' => 'required|string',
        ]);

        $invitation = Invitation::where('token', $validated['invite'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$invitation || strcasecmp($invitation->email, $validated['email']) !== 0) {
            return back()->withErrors(['invite' => 'This invitation is invalid, expired, or assigned to another email.'])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $invitation->role,
        ]);

        $invitation->update(['accepted_at' => now()]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('businesses.create');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard');
    }
}