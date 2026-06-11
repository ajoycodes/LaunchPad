@extends('layouts.app')

@section('title', 'Sign up — LaunchPad')

@section('content')
    <div class="auth-wrap container-app">
        <div class="auth-card">
            <h1 class="auth-card__title">Create your account</h1>
            <p class="auth-card__subtitle">Join the makers shipping on LaunchPad.</p>

            <form method="POST" action="{{ route('register') }}" class="auth-form" novalidate>
                @csrf

                <div class="form-field">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required>
                    @error('username') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <span class="form-label">I'm joining as a…</span>
                    <div class="role-options">
                        <label class="role-option">
                            <input type="radio" name="role" value="maker" @checked(old('role') === 'maker')>
                            <span class="role-option__body">
                                <strong>🛠 Maker</strong>
                                <small>I build and launch products.</small>
                            </span>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="hunter" @checked(old('role', 'hunter') === 'hunter')>
                            <span class="role-option__body">
                                <strong>🔍 Hunter</strong>
                                <small>I discover and upvote products.</small>
                            </span>
                        </label>
                    </div>
                    @error('role') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn-accent auth-form__submit">Create account</button>
            </form>

            <p class="auth-card__alt">
                Already have an account? <a href="{{ route('login') }}">Log in</a>
            </p>
        </div>
    </div>
@endsection
