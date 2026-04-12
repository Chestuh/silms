@extends('layouts.app')

@section('title', 'Credential request')

@section('content')
<h2 class="mb-4"><i class="bi bi-file-earmark-text me-2"></i>Credential request</h2>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3 text-muted">Student</dt><dd class="col-sm-9">{{ $credential_request->student->user->name ?? '—' }}</dd>
            <dt class="col-sm-3 text-muted">Student No.</dt><dd class="col-sm-9">{{ $credential_request->student->student_number ?? '—' }}</dd>
            <dt class="col-sm-3 text-muted">Credential type</dt><dd class="col-sm-9">{{ $credential_request->credential_type }}</dd>
            <dt class="col-sm-3 text-muted">Status</dt><dd class="col-sm-9"><span class="badge bg-{{ $credential_request->status === 'processing' ? 'info' : 'warning' }}">{{ ucfirst($credential_request->status) }}</span></dd>
            <dt class="col-sm-3 text-muted">Requested</dt><dd class="col-sm-9">{{ \Carbon\Carbon::parse($credential_request->created_at)->format('F j, Y') }}</dd>
            <dt class="col-sm-3 text-muted">Payment cleared</dt><dd class="col-sm-9">@if($credential_request->payment_cleared_at)<span class="text-success"><i class="bi bi-check-circle me-1"></i>{{ \Carbon\Carbon::parse($credential_request->payment_cleared_at)->format('M j, Y') }}</span>@else<span class="text-muted">— Cashier can clear after payment</span>@endif</dd>
        </dl>
    </div>
</div>
@if($credential_request->letter_path && Storage::disk('local')->exists($credential_request->letter_path))
    <a href="{{ route('admin.credentials.letter', $credential_request) }}" class="btn btn-outline-secondary me-2" target="_blank"><i class="bi bi-download me-1"></i>Download signed letter</a>
@endif

<!-- E-sign button + modal -->
<!-- Button to open signature modal -->
<button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#signatureModal">
    <i class="bi bi-pen-fill me-1"></i> E-sign letter
</button>

<!-- Signature modal -->
<div class="modal fade" id="signatureModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.credentials.signature') }}" id="signatureForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">E-sign letter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Draw your signature below (use mouse or touch).</p>
                    <div class="mb-2">
                        <label class="form-label">Full name (printed)</label>
                        <input type="text" name="signer_name" id="signerNameInput" class="form-control mb-2" value="{{ auth()->user()->name ?? '' }}" required>
                    </div>
                    <div class="border p-2 mb-2">
                        <canvas id="signatureCanvas" width="600" height="160" style="touch-action: none; width:100%;"></canvas>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="sigClear">Clear</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="sigUndo">Undo</button>
                    </div>
                    <input type="hidden" name="signature_data" id="signatureData">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="sigSave">Save signature</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($credential_request->status !== 'ready')
    <form method="POST" action="{{ route('admin.credentials.send', $credential_request) }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Send signed letter to student</button>
    </form>
@else
    <span class="text-success small"><i class="bi bi-check-circle me-1"></i>Letter ready and sent to student. They can download it from their Credential Request page.</span>
@endif
<p class="small text-muted mt-3 mb-0">Use the E-sign button to capture the Principal's printed name and e-signature, then click “Send signed letter to student” to generate the signed PDF and make it available for the student to download.</p>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.credentials.index') }}">&larr; Back to credential requests</a></p>
@endsection

@push('scripts')
<script>
// Robust canvas signature capture using Pointer Events and devicePixelRatio scaling
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signatureCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const DPR = window.devicePixelRatio || 1;

    let strokes = []; // array of strokes (each stroke is array of points)
    let current = null;
    let isDrawing = false;

    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        const w = Math.max(300, rect.width);
        const h = 160;
        canvas.width = Math.round(w * DPR);
        canvas.height = Math.round(h * DPR);
        canvas.style.height = h + 'px';
        canvas.style.width = '100%';
        // Reset transform then scale for DPR
        ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';
        ctx.lineWidth = 2;
        redraw();
    }

    function getPoint(e) {
        const r = canvas.getBoundingClientRect();
        const x = (e.clientX !== undefined ? e.clientX : (e.touches && e.touches[0] && e.touches[0].clientX)) - r.left;
        const y = (e.clientY !== undefined ? e.clientY : (e.touches && e.touches[0] && e.touches[0].clientY)) - r.top;
        return { x: x, y: y };
    }

    function redraw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.beginPath();
        for (const s of strokes) {
            if (!s || s.length === 0) continue;
            ctx.moveTo(s[0].x, s[0].y);
            for (let i = 1; i < s.length; i++) ctx.lineTo(s[i].x, s[i].y);
        }
        ctx.stroke();
    }

    canvas.addEventListener('pointerdown', function(e) {
        e.preventDefault();
        canvas.setPointerCapture(e.pointerId);
        isDrawing = true;
        current = [];
        strokes.push(current);
        current.push(getPoint(e));
        redraw();
    });
    canvas.addEventListener('pointermove', function(e) {
        if (!isDrawing || !current) return;
        e.preventDefault();
        current.push(getPoint(e));
        redraw();
    });
    ['pointerup','pointercancel','pointerout','pointerleave'].forEach(evt => {
        canvas.addEventListener(evt, function(e) {
            if (isDrawing) {
                isDrawing = false;
                try { canvas.releasePointerCapture(e.pointerId); } catch (err) {}
            }
        });
    });

    document.getElementById('sigClear').addEventListener('click', function(){ strokes = []; redraw(); });
    document.getElementById('sigUndo').addEventListener('click', function(){ strokes.pop(); redraw(); });

    document.getElementById('signatureForm').addEventListener('submit', function(e) {
        const dataUrl = canvas.toDataURL('image/png');
        document.getElementById('signatureData').value = dataUrl;
    });

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
});
</script>
@endpush
