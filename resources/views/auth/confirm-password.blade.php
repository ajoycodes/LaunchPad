@extends('layouts.app')

@section('title', 'Confirm Password — LaunchPad')

@section('content')
    <div class="auth-simple container-app">
        <div class="auth-simple__card">
            <div class="auth-simple__brand">
                <i data-lucide="rocket"></i> LaunchPad
            </div>

            <h1>Confirm your password</h1>
            <p class="auth-simple__text">This is a secure area. Please confirm your password before continuing.</p>

            <form method="POST" action="{{ route('password.confirm') }}" class="auth-form" novalidate>
                @csrf

                <div class="form-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" placeholder="Your password" required autocomplete="current-password">
                    @error('password') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn-accent auth-form__submit">Confirm</button>
            </form>
        </div>
    </div>
@endsection
