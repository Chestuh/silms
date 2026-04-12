@extends('layouts.app')

@section('title', 'Pre-Registration Submitted')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body p-4 text-center">
                <div class="mb-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h2 class="card-title mb-3">Pre-Registration Submitted!</h2>
                <p class="text-muted mb-4">
                    Your pre-registration has been submitted successfully. Your information has been sent to the admin for review and approval.
                </p>
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading">What's Next?</h6>
                    <p class="mb-0">
                        You will receive an email notification once the admin approves your pre-registration. After approval, you will be able to login with your credentials and access your dashboard with your assigned School ID.
                    </p>
                </div>
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Go to Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
