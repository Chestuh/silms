@extends('layouts.app')

@section('title', 'Rate Material')

@section('content')
<h2 class="mb-4">Rate Learning Material</h2>
<p class="text-muted">{{ $material->title }}</p>
<form method="POST" action="{{ route('student.rate.update', $material) }}" class="card p-4" style="max-width: 400px;">
    @csrf
    <div class="mb-3">
        <label class="form-label">Rating (1 to 5)</label>
        <select name="rating" class="form-select" required>
            @for($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}" @if($rating && $rating->rating == $i) selected @endif>{{ $i }} star(s)</option>
            @endfor
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Comment (optional)</label>
        <textarea name="comment" class="form-control" rows="3">{{ $rating?->comment ?? '' }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save Rating</button>
</form>
<p class="mt-3"><a href="{{ route('student.learning.index') }}">Back to Learning Materials</a></p>
@endsection
