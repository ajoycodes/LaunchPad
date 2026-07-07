@extends('layouts.app')

@section('title', 'Forgot Password — LaunchPad')

@section('content')
    <div class="auth-simple container-app">
        <div class="auth-simple__card">
            <div class="auth-simple__brand">
                <i data-lucide="rocket"></i> LaunchPad
            </div>

            <h1>Forgot your password?</h1>
            <p class="auth-simple__text">
                No problem. Enter your email and we'll send you a link to choose a new one.
            </p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="auth-form" novalidate>
                @csrf

                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-accent auth-form__submit">Email password reset link</button>
            </form>

            <p class="auth-split__alt">
                <a href="{{ route('login') }}">Back to log in</a>
            </p>
        </div>
    </div>
@endsection
