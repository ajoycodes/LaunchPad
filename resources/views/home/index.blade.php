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

            {{-- Search bar --}}
            <form method="GET" action="{{ route('home') }}" class="feed-search" role="search">
                @foreach(request()->except('q', 'page') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <div class="feed-search__wrap">
                    <i data-lucide="search" class="feed-search__icon"></i>
                    <input type="search" name="q" value="{{ $search }}"
                           placeholder="Search products…" class="feed-search__input" autocomplete="off">
                    @if($search)
                        <a href="{{ route('home', request()->except('q', 'page')) }}" class="feed-search__clear" aria-label="Clear search">
                            <i data-lucide="x"></i>
                        </a>
                    @endif
                </div>
            </form>

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

            {{-- Featured product --}}
            @if($featured)
                <div class="sidebar-card">
                    <h3 class="sidebar-card__heading">Featured</h3>
                    <a href="{{ route('products.show', $featured) }}" class="featured-card">
                        <div class="featured-card__logo">
                            @if($featured->logo)
                                <img src="{{ Storage::url($featured->logo) }}" alt="{{ $featured->name }}">
                            @else
                                <i data-lucide="box"></i>
                            @endif
                        </div>
                        <div class="featured-card__body">
                            <div class="featured-card__name">{{ $featured->name }}</div>
                            <div class="featured-card__tagline">{{ $featured->tagline }}</div>
                        </div>
                    </a>
                </div>
            @endif

            {{-- Top makers this week --}}
            @if($topMakers->count())
                <div class="sidebar-card">
                    <h3 class="sidebar-card__heading">Top Makers This Week</h3>
                    <div class="sidebar-makers">
                        @foreach($topMakers as $i => $maker)
                            <a href="{{ route('makers.show', $maker->username) }}" class="sidebar-maker sidebar-maker--link">
                                <span class="sidebar-maker__rank">{{ $i + 1 }}</span>
                                <div class="sidebar-maker__avatar">
                                    @if($maker->avatar)
                                        <img src="{{ Storage::url($maker->avatar) }}" alt="{{ $maker->name }}">
                                    @else
                                        <span>{{ strtoupper(substr($maker->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="sidebar-maker__info">
                                    <span class="sidebar-maker__name">{{ $maker->name }}</span>
                                    <span class="sidebar-maker__sub">{{ $maker->products_count }} launch{{ $maker->products_count !== 1 ? 'es' : '' }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Popular tags --}}
            @if($popularTags->count())
                <div class="sidebar-card">
                    <h3 class="sidebar-card__heading">Popular Tags</h3>
                    <div class="sidebar-tags">
                        @foreach($popularTags as $tag)
                            <a href="{{ route('home', ['q' => $tag->name]) }}"
                               class="sidebar-tag">
                                {{ $tag->name }}
                                <span class="sidebar-tag__count">{{ $tag->products_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </aside>

    </div>
</div>

@endsection
