@extends('layouts.app')

@section('title', 'LaunchPad — Discover and launch new products')

@section('content')

{{-- Sub-header (tabs + search + category pills) --}}
<div class="feed-subheader">
    <div class="container-app feed-subheader__inner">

        {{-- Tab bar + submit CTA --}}
        <div class="feed-tabs-row">
            <nav class="feed-tabs" aria-label="Time filter">
                @foreach(['today' => 'Today', 'week' => 'This Week', 'alltime' => 'All Time'] as $key => $label)
                    <a href="{{ route('home', array_merge(request()->except('tab', 'page'), ['tab' => $key])) }}"
                       class="feed-tab @if($tab === $key) active @endif">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            @auth
                @if(auth()->user()->isMaker() || auth()->user()->isAdmin())
                    <a href="{{ route('products.create') }}" class="btn-accent btn-sm">
                        <i data-lucide="plus" class="icon-inline"></i> Submit
                    </a>
                @endif
            @endauth
        </div>

        {{-- Category pills --}}
        <div class="feed-categories">
            <a href="{{ route('home', request()->except('category', 'page')) }}"
               class="feed-category-pill @if(!$catSlug) active @endif">
                All
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('home', array_merge(request()->except('category', 'page'), ['category' => $cat->slug])) }}"
                   class="feed-category-pill @if($catSlug === $cat->slug) active @endif">
                    <i data-lucide="{{ $cat->icon }}"></i>
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

    </div>
</div>

{{-- Main content --}}
<div class="container-app">
    <div class="feed-layout">

        {{-- Product list --}}
        <main class="feed-main">

            {{-- Product list --}}
            @if($products->isEmpty())
                <div class="feed-empty">
                    <i data-lucide="inbox" class="feed-empty__icon"></i>
                    <p>No products here yet.</p>
                    <a href="{{ route('products.create') }}" class="btn-accent">Be the first to launch</a>
                </div>
            @else
                <div class="feed-list">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <div class="feed-pagination">
                    {{ $products->links() }}
                </div>
            @endif

        </main>

        {{-- Sidebar --}}
        <aside class="feed-sidebar">
            <p class="text-muted" style="font-size:.85rem;">Sidebar coming soon.</p>
        </aside>

    </div>
</div>

@endsection
