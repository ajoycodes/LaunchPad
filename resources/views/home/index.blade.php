@extends('layouts.app')

@section('title', 'LaunchPad — Discover and launch new products')

@section('content')
    <section class="home-hero container-app">
        <span class="home-hero__eyebrow"><i data-lucide="rocket" class="icon-inline"></i> Welcome to LaunchPad</span>
        <h1 class="home-hero__title">Discover what makers are building today.</h1>
        <p class="home-hero__subtitle">
            Launch your product, collect upvotes, and get honest feedback from a
            community that actually ships. The feed goes live as makers start launching.
        </p>
        <div class="home-hero__actions">
            <a href="{{ url('/register') }}" class="btn-accent">Join the community</a>
            <a href="{{ url('/products/create') }}" class="btn-ghost">Submit a product</a>
        </div>
    </section>
@endsection
