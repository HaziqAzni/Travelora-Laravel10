<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (current_user()) {
            return redirect('/');
        }
        $redirect = safe_redirect((string) $request->query('redirect', '/my-bookings'), '/my-bookings');
        $error    = null;
        return view('auth.login', compact('redirect', 'error'));
    }

    public function login(Request $request)
    {
        if (current_user()) {
            return redirect('/');
        }

        $redirect = safe_redirect((string) $request->input('redirect', '/my-bookings'), '/my-bookings');
        $email    = trim((string) $request->input('email'));
        $password = (string) $request->input('password');
        $error    = null;

        if ($email === '' || $password === '') {
            $error = t('err.required', 'Please enter your email and password.');
        } else {
            $user = User::where('email', $email)->first();
            if ($user && password_verify($password, $user->password)) {
                // Prevent session fixation
                $request->session()->regenerate();
                session([
                    'user' => [
                        'id'    => $user->id,
                        'name'  => $user->name,
                        'email' => $user->email,
                        'role'  => $user->role ?? 'user',
                    ],
                ]);
                set_flash('success', sprintf(t('flash.welcome_back', 'Welcome back, %s!'), $user->name));
                return redirect($redirect);
            }
            $error = t('err.invalid_login', 'Incorrect email or password.');
        }

        return view('auth.login', compact('redirect', 'error'));
    }

    public function showSignup(Request $request)
    {
        if (current_user()) {
            return redirect('/');
        }
        $error = null;
        return view('auth.signup', compact('error'));
    }

    public function signup(Request $request)
    {
        if (current_user()) {
            return redirect('/');
        }

        $name     = trim((string) $request->input('name'));
        $email    = trim((string) $request->input('email'));
        $password = (string) $request->input('password');
        $confirm  = (string) $request->input('confirm');
        $error    = null;

        if ($name === '' || $email === '' || $password === '') {
            $error = t('err.fill_all', 'Please fill in all fields.');
        } elseif (strlen($name) < 2) {
            $error = t('err.name_short', 'Please enter your full name.');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = t('err.email_invalid', 'Please enter a valid email address.');
        } elseif (strlen($password) < 6) {
            $error = t('err.pwd_short', 'Password must be at least 6 characters.');
        } elseif ($password !== $confirm) {
            $error = t('err.pwd_match', 'Passwords do not match.');
        } elseif (User::where('email', $email)->exists()) {
            $error = t('err.email_exists', 'An account with that email already exists. Try logging in.');
        } else {
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role'     => 'user',
            ]);

            $request->session()->regenerate();
            session(['user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'role' => $user->role]]);
            set_flash('success', sprintf(t('flash.signup_success', 'Welcome to Travelora, %s! Your account is ready.'), $name));
            return redirect('/');
        }

        return view('auth.signup', compact('error'));
    }

    public function logout(Request $request)
    {
        // Fully invalidate the session, not just the app's user value.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        set_flash('success', t('flash.logout', 'You have been logged out. See you on the next trip!'));
        return redirect('/');
    }
}
