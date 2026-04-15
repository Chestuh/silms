<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .badge-contrast {
            background-color: rgba(255, 255, 255, 0.95);
            color: #212529;
            border: 1px solid rgba(33, 37, 41, 0.12);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.65);
            font-weight: 600;
            font-size: 0.8rem;
        }

        .btn-group .badge-contrast {
            min-width: 1.7rem;
            text-align: center;
        }
    </style>
    <link rel="icon" href="{{ asset('assets/icons/logo.png') }}" type="image/x-icon">
    @if(request()->routeIs('home'))
    <style>
        /* 3D spinning logo overlay */
        .welcome-overlay {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(6px);
            z-index: 2000;
            pointer-events: auto;
        }
        .welcome-card {
            background: rgba(255,255,255,0.96);
            border-radius: 12px;
            padding: 28px 32px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.25);
            text-align: center;
            max-width: 420px;
            width: 90%;
            perspective: 900px;
        }
        .welcome-logo {
            width: 108px;
            height: 108px;
            margin: 0 auto 12px;
            display: block;
        }
        .welcome-title { font-weight: 700; font-size: 1.25rem; color: #0b2545; }
        .welcome-sub { color: #3a5a78; font-size: .95rem; margin-top:6px }
        @media (prefers-reduced-motion: reduce) {
            .welcome-logo, .threeD { transform: none !important; box-shadow: none !important; }
        }
    </style>
    @endif
</head>
<body class="d-flex flex-column min-vh-100">
@if(request()->routeIs('home'))
<!-- Welcome overlay shown on page load -->
<div id="welcomeOverlay" class="welcome-overlay" role="dialog" aria-label="Welcome">
    <div class="welcome-card">
        <img src="{{ asset('assets/icons/logo.png') }}" alt="CSP logo" class="welcome-logo threeD">
        <div class="welcome-title">Welcome to CSP Learning Portal</div>
        <div class="welcome-sub">Christian School of Polomolok, Inc. Learning Portal System</div>
    </div>
</div>
@endif
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid px-3 px-lg-4">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav">
                @auth
                    @if(auth()->user()->role === 'student')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Student Info</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('student.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.gwa') }}">GWA & Grades</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.academic-status') }}">Academic Status</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.admission') }}">Admission Records</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.disciplinary') }}">Disciplinary Records</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.honors') }}">Academic Honors</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.fees') }}">Fees & Payments</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Learning</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('student.learning.index') }}">My Learning Materials</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.progress') }}">Learning Progress</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.reminders.index') }}">Study Reminders</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Portal</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('student.messages') }}">Messages</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.performance') }}">Performance & Reports</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.credentials.index') }}">Credential Request</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.self-assessment') }}">Self-Assessment</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.learning-path.index') }}">Learning Path</a></li>
                                <li><a class="dropdown-item" href="{{ route('student.dashboard-personalization') }}">Customize Dashboard</a></li>
                            </ul>
                        </li>
                    @elseif(auth()->user()->role === 'instructor')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Instructor</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('instructor.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.courses.index') }}">Courses</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.materials.index') }}">Materials</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.grades.index') }}">Grades</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.gwa.index') }}">Students GWA</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.academic-status.index') }}">Academic Status</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.academic-load.index') }}">Academic Load</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.honors.index') }}">Academic Honors</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.progress.index') }}">Progress</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.skill-gaps.index') }}">Skill Gaps Report</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.messages.index') }}">Messages</a></li>
                                <li><a class="dropdown-item" href="{{ route('instructor.rubrics.index') }}">Rubrics</a></li>
                            </ul>
                        </li>
                    @elseif(auth()->user()->role === 'admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Admin</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.students.index') }}">Students</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.instructors.index') }}">Instructors</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.courses.index') }}">Courses</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.materials.index') }}">Materials</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.enrollments.index') }}">Enrollments</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.credentials.index') }}">Credentials</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.reports.index') }}">Reports</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.academic-status.index') }}">Academic Status</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.admission.index') }}">Admission Records</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.disciplinary.index') }}">Disciplinary Records</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.academic-load.index') }}">Academic Load</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.progress.index') }}">Learning progress</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.learning-path.index') }}">Learning path</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">System defaults</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.transfer-requests.index') }}">Transfer Requests</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.skill-gaps.index') }}">Skill Gaps Report</a></li>
                            </ul>
                        </li>
                    @elseif(auth()->user()->role === 'cashier')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Cashier</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('cashier.dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('cashier.fees.index') }}">Fees & payments</a></li>
                                <li><a class="dropdown-item" href="{{ route('cashier.credentials.index') }}">Credential clearance</a></li>
                                <li><a class="dropdown-item" href="{{ route('cashier.admission.index') }}">Admission records</a></li>
                                <li><a class="dropdown-item" href="{{ route('cashier.students.index') }}">Students (view)</a></li>
                            </ul>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small text-muted">{{ ucfirst(auth()->user()->role) }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person-gear me-2"></i>Account Center</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<main class="container flex-grow-1 py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</main>
<footer class="bg-dark text-light py-4 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h6>{{ config('app.name') }}</h6>
                <p class="small text-secondary mb-0">{{ config('app.tagline') }}</p>
            </div>
            <div class="col-md-6 text-md-end small text-secondary">
                &copy; {{ date('Y') }} CSP Learning Portal.
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@if(request()->routeIs('home'))
<script>
    (function(){
        const overlay = document.getElementById('welcomeOverlay');
        if(!overlay) return;
        // Auto-hide after 3.5 seconds
        const timer = setTimeout(() => {
            overlay.style.transition = 'opacity .35s ease';
            overlay.style.opacity = '0';
            setTimeout(()=> overlay.remove(), 450);
        }, 3500);
        // Allow click to dismiss immediately
        overlay.addEventListener('click', (e)=>{
            if(e.target === overlay || e.target.closest('.welcome-card')) {
                clearTimeout(timer);
                overlay.style.transition = 'opacity .2s ease';
                overlay.style.opacity = '0';
                setTimeout(()=> overlay.remove(), 250);
            }
        });
    })();
</script>
@endif

@stack('scripts')
</body>
</html>
