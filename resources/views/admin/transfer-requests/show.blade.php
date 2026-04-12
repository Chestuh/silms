@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Transfer Request #{{ $transferRequest->id }}</h3>
    <p><strong>Student:</strong> {{ optional($transferRequest->student->user)->email ?? '—' }}</p>
    <p><strong>From:</strong> {{ $transferRequest->from_school }}</p>
    <p><strong>To:</strong> {{ $transferRequest->to_school }}</p>
    <p><strong>Notes:</strong><br>{{ $transferRequest->notes }}</p>
    <p><strong>Status:</strong> {{ $transferRequest->status }}</p>
    <p>
        @if($transferRequest->status === 'pending')
            <form method="POST" action="{{ route('admin.transfer-requests.approve', $transferRequest) }}" style="display:inline">@csrf<button class="btn btn-success">Approve</button></form>
            <form method="POST" action="{{ route('admin.transfer-requests.reject', $transferRequest) }}" style="display:inline">@csrf<button class="btn btn-danger">Reject</button></form>
        @endif
        <a href="{{ route('admin.transfer-requests.index') }}" class="btn btn-secondary">Back</a>
    </p>
</div>
@endsection
