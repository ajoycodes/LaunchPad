@extends('layouts.app')

@section('title', 'Log in — LaunchPad')

@section('content')
    <div class="auth-wrap container-app">
        <div class="auth-card">
            <h1 class="auth-card__title">Welcome back</h1>
            <p class="auth-card__subtitle">Log in to upvote, comment, and launch.</p>

            <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
                @csrf

                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                    @error('password') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <label class="form-check-inline">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="btn-accent auth-form__submit">Log in</button>
            </form>

            <p class="auth-card__alt">
                New to LaunchPad? <a href="{{ route('register') }}">Create an account</a>
            </p>
        </div>
    </div>
@endsection
