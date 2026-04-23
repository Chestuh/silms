@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<h2 class="mb-4"><i class="bi bi-bell me-2"></i>Notifications</h2>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>All Notifications</span>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">Mark all as read</button>
            </form>
        @endif
    </div>
    <ul class="list-group list-group-flush">
        @forelse($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-center {{ $notification->read_at ? 'text-muted' : '' }}">
                <div class="d-flex align-items-start gap-3">
                    @if($notification->read_at)
                        <i class="bi bi-bell text-muted mt-1"></i>
                    @else
                        <i class="bi bi-bell-fill text-primary mt-1"></i>
                    @endif
                    <div>
                        <strong>{{ $notification->data['title'] ?? 'Reminder' }}</strong>
                        @if(isset($notification->data['remind_at']))
                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($notification->data['remind_at'])->format('M j, Y g:i A') }}</small>
                        @endif
                        <br><small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @if(!$notification->read_at)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Mark read</button>
                    </form>
                @endif
            </li>
        @empty
            <li class="list-group-item text-muted">No notifications yet.</li>
        @endforelse
    </ul>
</div>

<div class="mt-3">
    {{ $notifications->links() }}
</div>
@endsection