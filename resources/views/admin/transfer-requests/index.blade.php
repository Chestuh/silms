@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Transfer Requests</h3>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <table class="table">
        <thead><tr><th>ID</th><th>Student</th><th>From</th><th>To</th><th>Status</th><th>Requested</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($requests as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ optional($r->student->user)->email ?? '—' }}</td>
                <td>{{ $r->from_school }}</td>
                <td>{{ $r->to_school }}</td>
                <td>{{ ucfirst($r->status) }}</td>
                <td>{{ $r->requested_at }}</td>
                <td>
                    <a href="{{ route('admin.transfer-requests.show', $r) }}" class="btn btn-sm btn-outline-primary">View</a>
                    @if($r->status === 'pending')
                        <form method="POST" action="{{ route('admin.transfer-requests.approve', $r) }}" style="display:inline">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                        <form method="POST" action="{{ route('admin.transfer-requests.reject', $r) }}" style="display:inline">@csrf<button class="btn btn-sm btn-danger">Reject</button></form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
