@extends('layouts.email')

@section('subject', 'Application Approved')

@section('content')
<p>Dear {{ $preRegistration->first_name }},</p>

<p>Good news — your application has been approved by the admissions team.</p>

<p>Your student account has been created and is now active. You can log in at <a href="{{ url('/login') }}">{{ url('/login') }}</a> using your email address (<strong>{{ $preRegistration->email }}</strong>) and the password you used when you applied.</p>

@if(!empty($preRegistration->school_id))
<p>Your School ID: <strong>{{ $preRegistration->school_id }}</strong></p>
@endif

<p>Please contact the registrar if you need assistance.</p>

<p>Regards,<br>The Admissions Team</p>
@endsection
