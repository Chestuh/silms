@extends('layouts.app')

@section('title', 'Credential Request')

@section('content')
<h2 class="mb-4"><i class="bi bi-file-earmark-check me-2"></i>School Credential Request</h2>
<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-header">New request</div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.credentials.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Credential type</label>
                        <select name="credential_type" class="form-select" required>
                            <option value="" selected disabled>Select credential type</option>
                            <option value="Good Moral">Good Moral</option>
                            <option value="Form 137-A">Form 137-A</option>
                            <option value="Transcript of Records">Transcript of Records</option>
                            <option value="Certificate of Enrollment">Certificate of Enrollment</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Your requests</div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Type</th><th>Status</th><th>Requested</th><th>Attachment</th></tr></thead>
                    <tbody>
                        @foreach($requests as $r)
                        <tr>
                            <td>{{ $r->credential_type }}</td>
                            <td><span class="badge bg-{{ $r->status === 'released' ? 'success' : ($r->status === 'ready' ? 'info' : 'secondary') }}">{{ ucfirst($r->status) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($r->created_at)->format('M j, Y') }}</td>
                            <td>
                                @if($r->letter_path)
                                    <a href="{{ route('student.credentials.letter.download', $r) }}" class="btn btn-sm btn-outline-success" target="_blank"><i class="bi bi-download me-1"></i>Download letter (PDF)</a>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if($requests->isEmpty())
                        <tr><td colspan="4" class="text-center text-muted">No requests yet.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
