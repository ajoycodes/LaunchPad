@extends('layouts.app')

@section('title', 'Verify Email — LaunchPad')

@section('content')
    <div class="auth-simple container-app">
        <div class="auth-simple__card">
            <div class="auth-simple__brand">
                <i data-lucide="rocket"></i> LaunchPad
            </div>

            <h1>Verify your email</h1>
            <p class="auth-simple__text">
                Thanks for signing up! Click the link we emailed you to verify your address.
                Didn't get it? We can send another.
            </p>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success">A new verification link has been sent to your email address.</div>
            @endif

            <div class="auth-simple__row">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-accent">Resend verification email</button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-link-inline">Log out</button>
                </form>
            </div>
        </div>
    </div>
@endsection
