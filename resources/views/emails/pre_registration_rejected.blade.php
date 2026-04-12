@extends('layouts.email')

@section('subject', 'Application Rejected')

@section('content')
<p>Dear {{ $preRegistration->first_name }},</p>

<p>We regret to inform you that your application has been rejected.</p>

@if(!empty($reason))
<p><strong>Reason:</strong> {{ $reason }}</p>
@endif

<p>If you believe this was a mistake or wish to reapply, please contact the admissions office.</p>

<p>Regards,<br>The Admissions Team</p>
@endsection
