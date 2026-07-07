@extends('layouts.app')

@section('title', 'Log in — LaunchPad')
@section('hide-footer', '1')

@section('content')
    <div class="auth-split container-app">
        <div class="auth-split__card">

            <div class="auth-split__form-col">
                <div class="auth-split__form">
                    <h1 class="auth-split__title">Welcome back</h1>
                    <p class="auth-split__subtitle">Enter your email and password to access your account.</p>

                    <form method="POST" action="{{ route('login') }}" class="auth-form" novalidate>
                        @csrf

                        <div class="form-field">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                            @error('email') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-field">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" placeholder="Your password" required autocomplete="current-password">
                            @error('password') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="auth-form__row">
                            <label class="form-check-inline">
                                <input type="checkbox" name="remember">
                                <span>Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="auth-form__forgot">Forgot your password?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn-accent auth-form__submit">Log in</button>
                    </form>

                    <p class="auth-split__alt">
                        New to LaunchPad? <a href="{{ route('register') }}">Create an account</a>
                    </p>
                </div>

                <div class="auth-split__footer">
                    <span>© {{ date('Y') }} LaunchPad</span>
                    <a href="{{ route('home') }}">Back to home</a>
                </div>
            </div>

            <aside class="auth-split__promo" aria-hidden="true">
                <div class="auth-split__brand auth-split__brand--light">
                    <i data-lucide="rocket"></i> LaunchPad
                </div>

                <h2 class="auth-split__promo-title">Where makers launch and the community decides what wins.</h2>
                <p class="auth-split__promo-sub">Log in to upvote, comment, and ship your next product.</p>

                <div class="auth-promo-mock">
                    <div class="auth-promo-mock__row">
                        <span class="tile-initials tile-initials--sm auth-promo-mock__logo" style="--tile-bg:#0EA5E9">SF</span>
                        <span class="auth-promo-mock__info">
                            <strong>ShipFast</strong>
                            <small>Deploy your side project in minutes</small>
                        </span>
                        <span class="auth-promo-mock__vote"><i data-lucide="chevron-up"></i> 58</span>
                    </div>
                    <div class="auth-promo-mock__row">
                        <span class="tile-initials tile-initials--sm auth-promo-mock__logo" style="--tile-bg:#8B5CF6">MM</span>
                        <span class="auth-promo-mock__info">
                            <strong>MindMeld</strong>
                            <small>AI notes that capture decisions</small>
                        </span>
                        <span class="auth-promo-mock__vote"><i data-lucide="chevron-up"></i> 52</span>
                    </div>
                    <div class="auth-promo-mock__row">
                        <span class="tile-initials tile-initials--sm auth-promo-mock__logo" style="--tile-bg:#22C55E">GL</span>
                        <span class="auth-promo-mock__info">
                            <strong>GrowthLoop</strong>
                            <small>Referrals for bootstrapped SaaS</small>
                        </span>
                        <span class="auth-promo-mock__vote"><i data-lucide="chevron-up"></i> 39</span>
                    </div>
                </div>
            </aside>

        </div>
    </div>
@endsection
