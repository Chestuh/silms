@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<h2 class="mb-3"><i class="bi bi-chat-square-text me-2"></i>Messages</h2>
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
<div class="card border-0 shadow-sm chat-app-card">
    <div class="row g-0 flex-nowrap">
        <div class="col-md-4 border-end chat-list-wrap">
            <div class="p-2 border-bottom bg-light">
                <a href="{{ route('instructor.messages.create') }}" class="btn btn-primary btn-sm w-100"><i class="bi bi-plus-lg me-1"></i>New message</a>
            </div>
            <div class="chat-list list-group list-group-flush overflow-auto">
                @forelse($conversations as $c)
                    <a href="{{ route('instructor.messages.thread', $c['user']) }}" class="list-group-item list-group-item-action chat-list-item {{ isset($user) && $user->id === $c['user']->id ? 'active' : '' }}">
                        <div class="d-flex align-items-center">
                            <div class="chat-avatar me-2">{{ strtoupper(mb_substr($c['user']->name ?? '?', 0, 1)) }}</div>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="text-truncate">{{ $c['user']->name }}</strong>
                                    @if($c['unread'] > 0)<span class="badge bg-danger rounded-pill">{{ $c['unread'] }}</span>@endif
                                </div>
                                <small class="text-muted text-truncate d-block">{{ Str::limit($c['last_message']->body, 35) }}</small>
                            </div>
                            <small class="text-muted ms-1">{{ \Carbon\Carbon::parse($c['last_message']->created_at)->diffForHumans(null, true) }}</small>
                        </div>
                    </a>
                @empty
                    <div class="list-group-item text-muted text-center py-4">No conversations yet.<br><a href="{{ route('instructor.messages.create') }}">Start a message</a></div>
                @endforelse
            </div>
        </div>
        <div class="col-md-8 d-flex flex-column chat-thread-wrap">
            @if(isset($user))
                <div class="chat-header border-bottom p-2 bg-light d-flex align-items-center">
                    <div class="chat-avatar me-2">{{ strtoupper(mb_substr($user->name ?? '?', 0, 1)) }}</div>
                    <strong>{{ $user->name }}</strong>
                    @if($user->student ?? null)<span class="text-muted small ms-2">{{ $user->student->student_number ?? '' }}</span>@endif
                </div>
                <div class="chat-messages flex-grow-1 overflow-auto p-3" id="chatMessages">
                    @foreach($messages ?? [] as $msg)
                        <div class="message-row {{ $msg->sender_id === auth()->id() ? 'sent' : 'received' }}">
                            <div class="message-bubble">
                                <div class="message-body">{!! nl2br(e($msg->body)) !!}</div>
                                <small class="message-time text-muted">{{ \Carbon\Carbon::parse($msg->created_at)->format('M j, g:i A') }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="chat-input border-top p-2 bg-light">
                    <form method="POST" action="{{ route('instructor.messages.store') }}" class="d-flex gap-2 align-items-end">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <textarea name="body" class="form-control" placeholder="Type a message..." required maxlength="5000" rows="1" style="resize: none;"></textarea>
                        <button type="submit" class="btn btn-primary flex-shrink-0"><i class="bi bi-send-fill"></i></button>
                    </form>
                </div>
            @else
                <div class="flex-grow-1 d-flex align-items-center justify-content-center text-muted p-4">
                    <div class="text-center">
                        <i class="bi bi-chat-left-text display-4"></i>
                        <p class="mt-2 mb-0">Select a conversation or <a href="{{ route('instructor.messages.create') }}">start a new message</a></p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('chatMessages')?.scrollTo(0, 99999);
</script>
@endpush
@endsection
