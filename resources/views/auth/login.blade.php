@extends('layouts.app')
@section('title', 'Log In - Travelora')

@section('content')
<div class="auth-wrap">
    <div class="auth-aside">
        <h2>{!! t('auth.login.aside_h', 'Welcome back,<br>traveller.') !!}</h2>
        <p>{{ t('auth.login.aside_p', 'Log in to manage your bookings and keep exploring the world with Travelora.') }}</p>
    </div>

    <div class="auth-main">
        <div class="auth-card">
            <h1>{{ t('auth.login.title', 'Log In') }}</h1>
            <p class="lead">{{ t('auth.login.lead', 'Good to see you again. Enter your details below.') }}</p>

            @if($error)
                <div class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</div>
            @endif

            <form method="post" action="/login">
                @csrf
                <input type="hidden" name="redirect" value="{{ $redirect }}">
                <div class="form-field">
                    <label for="email">{{ t('auth.login.email', 'Email Address') }}</label>
                    <div class="control">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                    </div>
                </div>
                <div class="form-field">
                    <label for="password">{{ t('auth.login.password', 'Password') }}</label>
                    <div class="control">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="{{ t('auth.login.password', 'Password') }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ t('auth.login.submit', 'Log In') }}</button>
            </form>

            <p class="auth-alt">{{ t('auth.login.no', "Don't have an account?") }} <a href="/signup">{{ t('nav.signup', 'Sign Up') }}</a></p>
            @if(config('app.debug'))
                {{-- Demo credentials are shown only in local/demo mode, never in production. --}}
                <p class="auth-alt" style="margin-top:8px;font-size:13px">{{ t('auth.login.demo', 'Demo account (local only)') }}: <strong>demo@travelora.test</strong> / <strong>password123</strong></p>
            @endif
        </div>
    </div>
</div>
@endsection
