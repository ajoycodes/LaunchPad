@extends('layouts.app')

@section('title', 'Sign up — LaunchPad')
@section('hide-footer', '1')

@section('content')
    <div class="auth-split container-app">
        <div class="auth-split__card">

            <div class="auth-split__form-col">
                <div class="auth-split__brand">
                    <i data-lucide="rocket"></i> LaunchPad
                </div>

                <div class="auth-split__form">
                    <h1 class="auth-split__title">Create your account</h1>
                    <p class="auth-split__subtitle">Join the makers shipping on LaunchPad.</p>

                    <form method="POST" action="{{ route('register') }}" class="auth-form" novalidate>
                        @csrf

                        <div class="form-row">
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
                                        <span class="role-option__icon"><i data-lucide="hammer"></i></span>
                                        <strong>Maker</strong>
                                        <small>I build and launch products.</small>
                                    </span>
                                </label>
                                <label class="role-option">
                                    <input type="radio" name="role" value="hunter" @checked(old('role', 'hunter') === 'hunter')>
                                    <span class="role-option__body">
                                        <span class="role-option__icon"><i data-lucide="search"></i></span>
                                        <strong>Hunter</strong>
                                        <small>I discover and upvote products.</small>
                                    </span>
                                </label>
                            </div>
                            @error('role') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-field">
                                <label for="password">Password</label>
                                <input id="password" type="password" name="password" required autocomplete="new-password">
                                @error('password') <span class="form-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-field">
                                <label for="password_confirmation">Confirm password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <button type="submit" class="btn-accent auth-form__submit">Create account</button>
                    </form>

                    <p class="auth-split__alt">
                        Already have an account? <a href="{{ route('login') }}">Log in</a>
                    </p>
                </div>

                <div class="auth-split__footer">
                    <span>© {{ date('Y') }} LaunchPad</span>
                    <a href="{{ route('home') }}">Back to home</a>
                </div>
            </div>

            <aside class="auth-split__promo" aria-hidden="true">
                <h2 class="auth-split__promo-title">Ship it. Share it. Let the community decide.</h2>
                <p class="auth-split__promo-sub">Create an account to launch products, build in public, and earn badges.</p>

                <div class="auth-promo-mock">
                    <div class="auth-promo-mock__row">
                        <span class="tile-initials tile-initials--sm auth-promo-mock__logo" style="--tile-bg:#0EA5E9">DS</span>
                        <span class="auth-promo-mock__info">
                            <strong>DevSync</strong>
                            <small>Real-time code collaboration</small>
                        </span>
                        <span class="auth-promo-mock__vote"><i data-lucide="chevron-up"></i> 60</span>
                    </div>
                    <div class="auth-promo-mock__row">
                        <span class="tile-initials tile-initials--sm auth-promo-mock__logo" style="--tile-bg:#A855F7">PK</span>
                        <span class="auth-promo-mock__info">
                            <strong>PromptKit</strong>
                            <small>Prompt toolkit for LLM builders</small>
                        </span>
                        <span class="auth-promo-mock__vote"><i data-lucide="chevron-up"></i> 54</span>
                    </div>
                    <div class="auth-promo-mock__row">
                        <span class="tile-initials tile-initials--sm auth-promo-mock__logo" style="--tile-bg:#F97316">IS</span>
                        <span class="auth-promo-mock__info">
                            <strong>IconSmith</strong>
                            <small>Icon sets searchable by vibe</small>
                        </span>
                        <span class="auth-promo-mock__vote"><i data-lucide="chevron-up"></i> 47</span>
                    </div>
                </div>
            </aside>

        </div>
    </div>
@endsection
