@extends('layouts.app')

@section('title', 'Auto-Generated Learning Aids')

@section('content')
<h2 class="mb-4"><i class="bi bi-robot me-2"></i>AUTO-GENERATED LEARNING AIDS</h2>
<p class="text-muted">Upload multiple auxiliary resources and set per-file release date. Students unlock each file after completing the related material.</p>

<div class="mb-3">
    <a href="{{ route('instructor.learning-aids.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Learning Aid</a>
</div>

@if($aids->isEmpty())
    <div class="alert alert-info">No auto-generated learning aids yet.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Lesson</th>
                    <th scope="col">Course</th>
                    <th scope="col">Release Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aids as $aid)
                <tr>
                    <td>{{ $aid->title }}</td>
                    <td>{{ optional($aid->material)->title }}</td>
                    <td>{{ optional($aid->course)->code ?? optional($aid->material->course)->code }}</td>
                    <td>{{ $aid->release_at ? $aid->release_at->format('M j, Y H:i') : 'Not set' }}</td>
                    <td>{{ ucfirst($aid->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
