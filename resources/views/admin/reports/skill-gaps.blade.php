@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Skill Gaps Report</h3>
    @if(empty($reports))
        <div class="alert alert-info">No skill gaps detected.</div>
    @else
        @foreach($reports as $r)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ optional($r['student']->user)->email ?? 'Student '.$r['student']->id }}</h5>
                    <ul>
                        @foreach($r['gaps'] as $code => $reason)
                            <li><strong>{{ $code }}:</strong> {{ $reason }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
