@extends('layouts.app')

@section('title', 'Auto-Generated Learning Aids')

@section('content')
<h2 class="mb-4"><i class="bi bi-robot me-2"></i>AUTO-GENERATED LEARNING AIDS</h2>
<p class="text-muted">Resources become available after you complete a lesson and when the instructor-set release date has passed.</p>

@if($aids->isEmpty())
    <div class="alert alert-info">No learning aids available yet. Complete lessons to unlock scheduled materials.</div>
@else
    <div class="row g-3">
        @foreach($aids as $aid)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $aid->title }}</h5>
                    <p class="text-muted small">Lesson: {{ optional($aid->material)->title }}</p>
                    <p class="text-muted small">Release: {{ $aid->release_at->format('M j, Y H:i') }}</p>
                    <p class="card-text">{{ Str::limit($aid->description, 100) }}</p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('student.learning-aids.download', $aid) }}" class="btn btn-sm btn-primary">Download</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
