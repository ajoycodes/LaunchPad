@extends('layouts.app')

@section('title', '500 — Server Error')

@section('content')
<div class="container-app error-page">
    <div class="error-page__inner">
        <div class="error-page__code">500</div>
        <h1 class="error-page__title">Something went wrong</h1>
        <p class="error-page__message">An unexpected error occurred on our end. Please try again in a moment.</p>
        <a href="{{ url('/') }}" class="btn-accent">Back to home</a>
    </div>
</div>
@endsection
