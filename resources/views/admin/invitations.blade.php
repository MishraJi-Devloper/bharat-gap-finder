@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
    <div>
        <span class="badge bg-warning-subtle text-warning-emphasis mb-2">Private access</span>
        <h1 class="h3 fw-bold text-dark mb-1">Invite people or team members</h1>
        <p class="text-muted mb-0">Only people with a valid invitation can create an account and access the platform.</p>
    </div>
    <a href="{{ route('admin.businesses') }}" class="btn btn-outline-secondary">Review MSMEs</a>
</div>

@if(session('invite_url'))
    <div class="alert alert-success">
        <strong>Invitation created.</strong> Send this private link to the invited person:
        <div class="mt-2"><code>{{ session('invite_url') }}</code></div>
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card p-4">
            <h2 class="h5 fw-bold text-dark mb-3">Create invitation</h2>
            <form method="POST" action="{{ route('admin.invitations.store') }}">
                @csrf
                <label for="email" class="form-label fw-semibold">Person's email address</label>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" required placeholder="member@company.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <p class="small text-muted mt-2">The link expires after 7 days and can be used once.</p>
                <button class="btn btn-warning fw-semibold w-100">Generate private invitation</button>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card p-4">
            <h2 class="h5 fw-bold text-dark mb-3">Recent invitations</h2>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Email</th><th>Status</th><th>Expires</th></tr></thead>
                    <tbody>
                    @forelse($invitations as $invitation)
                        <tr><td>{{ $invitation->email }}</td><td>{{ $invitation->accepted_at ? 'Accepted' : 'Pending' }}</td><td>{{ $invitation->expires_at->format('d M Y') }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="text-muted text-center py-3">No invitations created yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection