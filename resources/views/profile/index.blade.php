@extends('layouts.app')

@section('title', 'Account Center')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h2 class="mb-4"><i class="bi bi-person-gear me-2"></i>Account Center</h2>

        <div class="card shadow mb-4">
            <div class="card-header"><h5 class="mb-0">Account Information</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <span class="text-muted small">Role: {{ ucfirst($user->role) }}</span>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header"><h5 class="mb-0">Customization and personalization</h5></div>
            <div class="card-body">
                <p class="small text-muted mb-0">Your dashboard and portal views are personalized by role. Students see GWA, academic load, learning progress, and study time; instructors see course and material KPIs; admins see school-wide metrics. Use the quick links on your dashboard to access features.</p>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header"><h5 class="mb-0">Change Password</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
