@extends('layouts.app')

@section('title', 'New Message')

@section('content')
<h2 class="mb-4"><i class="bi bi-plus-circle me-2"></i>New message</h2>
<p class="text-muted small mb-3">Choose a student to start a conversation.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @foreach($students as $u)
                <a href="{{ route('instructor.messages.thread', $u) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <div class="chat-avatar me-3">{{ strtoupper(mb_substr($u->name ?? '?', 0, 1)) }}</div>
                    <div>
                        <strong>{{ $u->name }}</strong>
                        <span class="text-muted small d-block">{{ $u->student->student_number ?? $u->email }}</span>
                    </div>
                    <i class="bi bi-chevron-right ms-auto text-muted"></i>
                </a>
            @endforeach
        </div>
        @if($students->isEmpty())
            <div class="p-4 text-center text-muted">No students in your courses.</div>
        @endif
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.messages.index') }}">&larr; Back to messages</a></p>
@endsection
