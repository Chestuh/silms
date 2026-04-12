@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- Hero Section --}}
<div class="landing-hero" style="background: linear-gradient(-50deg, #7b2ff7 0%, #2f80ed 35%, #14b8a6 50%);">
    <div class="container py-7 px-3 px-lg-5">
        <div class="row align-items-center gy-5">
            <div class="col-lg-7">
                <div class="hero-content">
                    <h3 class="display-4 fw-bold text-white mb-3" style="margin-left: 30px;">Christian School of Polomolok, Inc.</h3>
                    <p class="fs-6 mb-4 text-white-75" style="margin-left: 30px;">A integrated, unified learning portal for students. Track performance, monitor progress, and stay on top of every academic milestone.</p>
                    <br>
                    <div class="d-flex flex-wrap gap-3" style="margin-left: 30px;">
                        @guest
                        <a href="{{ route('login') }}" class="btn btn-cta-primary btn-lg px-5">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-cta-outline btn-lg px-5">
                            <i class="bi bi-person-plus me-2"></i>Enroll Now
                        </a>
                        @else
                        @php $r = auth()->user()->role; @endphp
                        <a href="{{ $r === 'student' ? route('student.dashboard') : ($r === 'instructor' ? route('instructor.dashboard') : ($r === 'admin' ? route('admin.dashboard') : route('cashier.dashboard'))) }}" class="btn btn-cta-primary btn-lg px-5">
                            <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                        </a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block" style="margin-left: -20px;">
                <div class="p-4 snapshot-card" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <h4>CSP Learning Portal All-in-One</h4>
                    <ul class="list-unstyled lh-lg">
                        <li><i class="bi bi-check-circle-fill me-2"></i>Student Information System</li>
                        <li><i class="bi bi-check-circle-fill me-2"></i>Learning Materials System</li>
                        <li><i class="bi bi-check-circle-fill me-2"></i>Integrated Student Portal System</li>
                        <li><i class="bi bi-check-circle-fill me-2"></i>Student-Teacher Communication Supported</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- {{-- Quick KPI strip --}}
<div class="py-4 bg-white border-top border-bottom">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-sm-6 col-md-3">
                <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="fs-1 fw-bold">24k+</div>
                    <div class="text-muted">Active users</div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="fs-1 fw-bold">1.2k</div>
                    <div class="text-muted">Active courses</div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="fs-1 fw-bold">95%</div>
                    <div class="text-muted">Completed assignments</div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="fs-1 fw-bold">33%</div>
                    <div class="text-muted">Average study engagement uplift</div>
                </div>
            </div>
        </div>
    </div>
</div> -->

{{-- Features Section --}}
<div class="features-section py-6">
    <div class="container-fluid px-3 px-lg-5">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold mb-3">Welcome to Christian School of Polomolok, Inc.</h2>
            <p class="fs-5 text-muted">A non-stock and non-profit educational institution offering kindergarten, elementary, junior high school and senior high school.</p>
        </div>
        
        <div class="row g-4">
            {{-- Feature 1 --}}
            <div class="col-lg-4 col-md-6">
                <div class="feature-box h-100">
                    <div class="feature-icon light-success">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5 class="fw-bold mt-4 mb-3">Junior High School</h5>
                    <p class="text-muted mb-3">Builds core academic knowledge and critical thinking skills across different subjects like Math, Science, and English.</p>
                    <ul class="feature-list">
                        <li><b><i>Grade 7</i></b></li>
                        <li><b><i>Grade 8</i></b></li>
                        <li><b><i>Grade 9</i></b></li>
                        <li><b><i>Grade 10</i></b></li>
                    </ul>
                </div>
            </div>
            {{-- Feature 2 --}}

            <div class="col-lg-4 col-md-6">
                <div class="feature-box h-100">
                    <div class="feature-icon light-primary">
                        <i class="bi bi-journals"></i>
                    </div>
                    <h5 class="fw-bold mt-4 mb-3">Vision and Mission of Christian School of Polomolok, Inc.</h5>
                    <p class="text-muted mb-3" style="text-align: justify;"><b>Vision</b><br>A Christian School responsive to the needs of its students faculty and staff to the glory of God.</p>
                    <p class="text-muted mb-3" style="text-align: justify;"><b>Mission</b><br>The Christian School of Polomolok aims to develop and train its students intellectually, physically, socially, culturally and morally to the Glory of God.</p>
                </div>
            </div>
            {{-- Feature 3 --}}
            <div class="col-lg-4 col-md-6">
                <div class="feature-box h-100">
                    <div class="feature-icon light-info">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5 class="fw-bold mt-4 mb-3">Senior High School</h5>
                    <p class="text-muted mb-3">Prepares students for college, careers, or specialized skills through focused tracks and advanced learning.</p>
                    <ul class="feature-list">
                        <li><b><i>Grade 11</i></b></li>
                        <li><b><i>Grade 12</i></b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CTA Section --}}
@guest
<div class="cta-section py-6 text-center">
    <div class="container-fluid px-3 px-lg-5">
        <h3 class="fw-bold mb-3">Available Senior High School Programs!</h3>
        <!-- Card Feature -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="feature-box h-150">
                    <ul class="feature-list">
                        <li><b><i>Accountancy, Business and Management (ABM)</i></b></li>
                        <li><b><i>General Academic Strand (GAS)</i></b></li>
                        <li><b><i>Science, Technology, and Engineering (STE)</i></b></li>
                        <li><b><i>TVL - Industrial Arts (IA)</i></b></li>
                        <li><b><i>TVL - Home Economics (HE)</i></b></li>
                        <li><b><i>TVL - Information and Communication Technology (ICT)</i></b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest
@endsection
