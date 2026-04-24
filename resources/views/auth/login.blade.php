@extends('layouts.app')

@section('title', 'Login')

@section('content')
<style>
    body {
        height: 100vh;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif
    }

    .login-container {
        min-height: calc(100vh - 160px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px 0;
    }

    .login-card {
        width: 100%;
        max-width: 900px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        display: flex;
        background: white;
        flex-wrap: wrap;
    }

    .login-left,
    .login-right {
        flex: 1 1 320px;
        min-width: 320px;
    }

    .login-left {
        padding: 40px;
        color: white;
        background: linear-gradient(135deg, #2200ff, #ff3b8d);
        position: relative;
    }

    .login-left h1 {
        font-weight: bold;
    }

    .login-left p {
        font-size: 16px;
        text-align: justify;
        opacity: 0.9;
    }

    .login-right {
        padding: 30px;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px 15px;
    }

    .btn-login {
        border-radius: 10px;
        background: linear-gradient(150deg, #ff3b8d, #2200ff);
        color: white;
        border: none;
        padding: 10px;
        font-weight: bold;
    }

    .btn-login:hover {
        opacity: 0.6;
    }

    .small-text {
        font-size: 12px;
        color: #888;
    }
</style>

<div class="login-container">
    <div class="login-card">

        <!-- LEFT SIDE -->
        <div class="login-left d-flex flex-column justify-content-center">
            <h1>Welcome!</h1>
            <p style="text-align: center;">
                CSP Learning Portal: A Web-based Student Information and Learning Materials System
            </p><br>
            <p>
                <b>Vision</b><br>
                A Christian School responsive to the needs of its students faculty and staff to the glory of God.
            </p>
            <p>
                <b>Mission</b><br>
                The Christian School of Polomolok aims to develop and train its students intellectually, physically, socially, culturally and morally to the Glory of God.
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="login-right">
            <h4 class="mb-4 text-center">User Login</h4>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <input type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login w-100">
                    LOGIN
                </button>
            </form>

            <!-- <p class="mt-4 text-center small-text">
                Demo Accounts:<br><br>
                <b>student@csp.edu</b><br>
                <b>instructor@csp.edu</b><br>
                <b>admin@csp.edu</b><br>
                <b>cashier@csp.edu</b><br>
                password: <kbd>password</kbd>
            </p> -->
        </div>

    </div>
</div>
@endsection