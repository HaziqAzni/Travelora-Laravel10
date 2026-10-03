@extends('layouts.app')
@section('title', 'Sign Up - Travelora')

@section('content')
<div class="auth-wrap">
    <div class="auth-aside">
        <h2>{!! t('auth.signup.aside_h', 'Start your next<br>adventure.') !!}</h2>
        <p>{{ t('auth.signup.aside_p', 'Create a free account to book hotels, flights and experiences in seconds.') }}</p>
    </div>

    <div class="auth-main">
        <div class="auth-card">
            <h1>{{ t('auth.signup.title', 'Create Account') }}</h1>
            <p class="lead">{{ t('auth.signup.lead', 'Join thousands of travellers booking with Travelora.') }}</p>

            @if($error)
                <div class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</div>
            @endif

            <form method="post" action="/signup">
                @csrf
                <div class="form-field">
                    <label for="name">{{ t('auth.signup.name', 'Full Name') }}</label>
                    <div class="control">
                        <i class="fa-regular fa-user"></i>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="{{ t('auth.signup.name_ph', 'e.g. Aisyah Rahman') }}" required>
                    </div>
                </div>
                <div class="form-field">
                    <label for="email">{{ t('auth.signup.email', 'Email Address') }}</label>
                    <div class="control">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label for="password">{{ t('auth.signup.password', 'Password') }}</label>
                        <div class="control">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="{{ t('auth.signup.password_ph', 'Min. 6 characters') }}" required>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="confirm">{{ t('auth.signup.confirm', 'Confirm Password') }}</label>
                        <div class="control">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="confirm" name="confirm" placeholder="{{ t('auth.signup.confirm_ph', 'Repeat password') }}" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ t('auth.signup.submit', 'Sign Up') }}</button>
            </form>

            <p class="auth-alt">{{ t('auth.signup.have', 'Already have an account?') }} <a href="/login">{{ t('nav.login', 'Log In') }}</a></p>
        </div>
    </div>
</div>
@endsection
