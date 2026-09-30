@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="mb-4 text-center">
            <h1 class="h3 fw-bold text-dark">Sign in</h1>
            <p class="text-muted mb-0">Manage your MSME profile and submissions.</p>
        </div>
        <div class="card p-4 p-lg-5">
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="email">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-check mb-4">
                    <input id="remember" type="checkbox" name="remember" class="form-check-input">
                    <label for="remember" class="form-check-label text-muted">Remember me</label>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">Sign in</button>
            </form>
            <p class="small text-center text-muted mt-4 mb-0">New here? <a href="{{ route('register') }}">Create an account</a></p>
        </div>
    </div>
</div>
@endsection