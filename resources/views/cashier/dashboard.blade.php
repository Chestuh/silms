@extends('layouts.app')

@section('title', 'Cashier Dashboard')

@section('content')
<div class="cashier-welcome rounded-4 p-5 mb-4" style="position: relative; overflow: hidden;">
    <div style="position: relative; z-index: 1;">
        <h2 class="mb-2" style="font-weight: 700; font-size: 2.2rem;">Welcome, {{ auth()->user()->name }}</h2>
        <p class="mb-0" style="font-size: 1.05rem; opacity: 0.95;">Financial KPIs and payment overview</p>
    </div>
    <div style="position: absolute; top: -40px; right: -100px; width: 350px; height: 350px; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 0;"></div>
</div>

<section class="mb-4">
    <h5 class="text-muted mb-3"><i class="bi bi-currency-exchange me-2"></i>Financial overview</h5>
    <div class="row g-3">
        <div class="col-6 col-md-4">
            <a href="{{ route('cashier.fees.index') }}" class="text-decoration-none text-dark">
                <div class="card admin-kpi h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="admin-kpi-icon bg-warning bg-opacity-10 rounded-3 p-2">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
                        </div>
                        <div>
                            <div class="admin-kpi-value fw-bold fs-4">{{ $kpis['pending_fees_count'] }}</div>
                            <div class="admin-kpi-label small text-muted">Pending fees</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="{{ route('cashier.fees.index') }}?status=pending" class="text-decoration-none text-dark">
                <div class="card admin-kpi h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="admin-kpi-icon bg-primary bg-opacity-10 rounded-3 p-2">
                            <i class="bi bi-cash-stack text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="admin-kpi-value fw-bold fs-4">₱{{ number_format($kpis['pending_fees_amount'], 0) }}</div>
                            <div class="admin-kpi-label small text-muted">Pending amount</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <div class="card admin-kpi h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="admin-kpi-icon bg-success bg-opacity-10 rounded-3 p-2">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <div>
                        <div class="admin-kpi-value fw-bold fs-4">₱{{ number_format($kpis['paid_today'], 0) }}</div>
                        <div class="admin-kpi-label small text-muted">Paid today</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <a href="{{ route('cashier.credentials.index') }}" class="text-decoration-none text-dark">
                <div class="card admin-kpi h-100 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 py-3">
                        <div class="admin-kpi-icon bg-info bg-opacity-10 rounded-3 p-2">
                            <i class="bi bi-file-earmark-text text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="admin-kpi-value fw-bold fs-4">{{ $kpis['credentials_awaiting_clearance'] }}</div>
                            <div class="admin-kpi-label small text-muted">Awaiting payment clearance</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <div class="card admin-kpi h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="admin-kpi-icon bg-secondary bg-opacity-10 rounded-3 p-2">
                        <i class="bi bi-file-check text-secondary fs-4"></i>
                    </div>
                    <div>
                        <div class="admin-kpi-value fw-bold fs-4">{{ $kpis['credentials_cleared'] }}</div>
                        <div class="admin-kpi-label small text-muted">Credentials cleared</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-semibold mb-2"><i class="bi bi-info-circle me-2 text-muted"></i>Cashier tools</h6>
        <p class="small text-muted mb-2">Review fees, mark payments, clear credentials for release, and verify admission payments.</p>
        <a href="{{ route('cashier.fees.index') }}" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-currency-exchange me-1"></i>Fees & payments</a>
        <a href="{{ route('cashier.credentials.index') }}" class="btn btn-sm btn-outline-info me-2"><i class="bi bi-file-earmark-text me-1"></i>Credential clearance</a>
        <a href="{{ route('cashier.admission.index') }}" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-journal-text me-1"></i>Admission records</a>
        <a href="{{ route('cashier.students.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-people me-1"></i>Students (view)</a>
    </div>
</div>
@endsection
