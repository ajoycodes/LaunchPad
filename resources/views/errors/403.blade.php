@extends('layouts.app')

@section('title', '403 — Forbidden')

@section('content')
<div class="container-app error-page">
    <div class="error-page__inner">
        <div class="error-page__code">403</div>
        <h1 class="error-page__title">Access denied</h1>
        <p class="error-page__message">You don't have permission to view this page.</p>
        <a href="{{ url('/') }}" class="btn-accent">Back to home</a>
    </div>
</div>
@endsection
