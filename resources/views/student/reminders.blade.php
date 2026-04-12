@extends('layouts.app')

@section('title', 'Study Reminders')

@section('content')
<h2 class="mb-4"><i class="bi bi-alarm me-2"></i>Study Reminder Scheduler</h2>
<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">Add reminder</div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.reminders.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link to material (optional)</label>
                        <select name="material_id" class="form-select">
                            <option value="">— None —</option>
                            @foreach($materials as $mat)
                            <option value="{{ $mat->id }}">{{ $mat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remind at</label>
                        <input type="datetime-local" name="remind_at" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Reminder</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Your reminders</div>
            <ul class="list-group list-group-flush">
                @foreach($reminders as $r)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $r->title }}</strong>
                        @if($r->material)<br><small class="text-muted">{{ $r->material->title }}</small>@endif
                        <br><small>{{ \Carbon\Carbon::parse($r->remind_at)->format('M j, Y g:i A') }}</small>
                    </div>
                    <form method="POST" action="{{ route('student.reminders.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Delete?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </li>
                @endforeach
                @if($reminders->isEmpty())
                <li class="list-group-item text-muted">No reminders. Add one above.</li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endsection
