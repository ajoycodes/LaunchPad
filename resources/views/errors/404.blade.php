@extends('layouts.app')

@section('title', '404 — Page Not Found')

@section('content')
<div class="container-app error-page">
    <div class="error-page__inner">
        <div class="error-page__code">404</div>
        <h1 class="error-page__title">Page not found</h1>
        <p class="error-page__message">We couldn't find the page you were looking for. It may have been moved or deleted.</p>
        <a href="{{ url('/') }}" class="btn-accent">Back to home</a>
    </div>
</div>
@endsection
