@extends('layouts.app')

@section('title', 'Reset Password — LaunchPad')

@section('content')
    <div class="auth-simple container-app">
        <div class="auth-simple__card">
            <div class="auth-simple__brand">
                <i data-lucide="rocket"></i> LaunchPad
            </div>

            <h1>Choose a new password</h1>
            <p class="auth-simple__text">Make it something you don't use anywhere else.</p>

            <form method="POST" action="{{ route('password.store') }}" class="auth-form" novalidate>
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <label for="password">New password</label>
                    <input id="password" type="password" name="password" placeholder="8+ characters" required autocomplete="new-password">
                    @error('password') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <label for="password_confirmation">Confirm new password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Repeat password" required autocomplete="new-password">
                    @error('password_confirmation') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-accent auth-form__submit">Reset password</button>
            </form>
        </div>
    </div>
@endsection
