@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="mb-4 text-center">
            <h1 class="h3 fw-bold text-dark">Create an account</h1>
            <p class="text-muted mb-0">Register securely before submitting an MSME profile.</p>
        </div>
        <div class="card p-4 p-lg-5">
            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <input type="hidden" name="invite" value="{{ request('invite') }}">
                @if(!$invitation)
                    <div class="alert alert-warning small">Registration requires a valid invitation link from an administrator.</div>
                @else
                    <div class="alert alert-success small">Invitation accepted for {{ $invitation->email }}.</div>
                @endif
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Full name</label>
                    <input id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autocomplete="name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autocomplete="email">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8" autocomplete="new-password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required minlength="8" autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">Create account</button>
                @error('invite')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </form>
            <p class="small text-center text-muted mt-4 mb-0">Already registered? <a href="{{ route('login') }}">Sign in</a></p>
        </div>
    </div>
</div>
@endsection