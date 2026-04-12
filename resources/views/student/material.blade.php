@extends('layouts.app')

@section('title', $material->title)

@section('content')
<h2 class="mb-4">{{ $material->title }}</h2>
<div class="card mb-3">
    <div class="card-body">
        <span class="badge bg-secondary">{{ $material->format }}</span>
        <span class="badge badge-difficulty-{{ $material->difficulty_level }}">{{ $material->difficulty_level }}</span>
        @if($material->course)<span class="text-muted">{{ $material->course->code }}</span>@endif
        @if($material->description)<p class="mt-2 mb-0">{!! nl2br(e($material->description)) !!}</p>@endif
    </div>
</div>
@if($material->url)
<div class="card"><div class="card-body"><a href="{{ $material->url }}" target="_blank" rel="noopener" class="btn btn-primary">Open link</a></div></div>
@elseif($material->file_path)
<div class="card"><div class="card-body"><a href="{{ asset('uploads/' . $material->file_path) }}" target="_blank" class="btn btn-primary">View file</a></div></div>
@else
<div class="alert alert-secondary">Content not configured for this material.</div>
@endif
@if(isset($availableAids) && $availableAids->isNotEmpty())
<div class="card mb-3">
    <div class="card-body">
        <h5>Available Auto-Generated Learning Aids</h5>
        <ul class="list-group list-group-flush">
            @foreach($availableAids as $aid)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $aid->title }}
                <a href="{{ route('student.learning-aids.download', $aid) }}" class="btn btn-sm btn-primary">Download</a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@if(isset($lockedAids) && $lockedAids->isNotEmpty())
<div class="card mb-3">
    <div class="card-body">
        <h5>Locked / Scheduled Aids</h5>
        <ul class="list-group list-group-flush">
            @foreach($lockedAids as $aid)
            <li class="list-group-item">
                {{ $aid->title }}
                <span class="badge bg-secondary ms-2">{{ $aid->status }}</span>
                <small class="text-muted">Release: {{ $aid->release_at ? $aid->release_at->format('M j, Y H:i') : 'TBD' }}</small>
            </li>
            @endforeach
        </ul>
        <p class="small text-muted mt-2">Complete the lesson (100%) and wait for the release date to access the files.</p>
    </div>
</div>
@endif

<p class="mt-3"><a href="{{ route('student.learning.index') }}" class="btn btn-outline-secondary">Back to Learning Materials</a></p>
@endsection
