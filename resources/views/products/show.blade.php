@extends('layouts.app')

@section('title', $product->name . ' — ' . $product->tagline)

@section('content')
<div class="container-app">
    <div class="product-show">

        {{-- Header --}}
        <div class="product-header">
            <div class="product-header__logo">
                @if($product->logo)
                    <img src="{{ Storage::url($product->logo) }}" alt="{{ $product->name }} logo">
                @else
                    <i data-lucide="box" class="product-header__logo-placeholder"></i>
                @endif
            </div>

            <div class="product-header__info">
                <div class="product-header__meta">
                    <span class="product-header__category">
                        <i data-lucide="{{ $product->category->icon }}" class="icon-inline"></i>
                        {{ $product->category->name }}
                    </span>
                    @if($product->status === 'pending')
                        <span class="product-status product-status--pending">Pending review</span>
                    @endif
                </div>
                <h1 class="product-header__name">{{ $product->name }}</h1>
                <p class="product-header__tagline">{{ $product->tagline }}</p>

                <div class="product-header__actions">
                    {{-- Upvote placeholder (Module 5) --}}
                    <button class="btn-upvote" disabled>
                        ▲ <span>Upvote</span>
                    </button>

                    @if($product->website_url)
                        <a href="{{ $product->website_url }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">Visit site ↗</a>
                    @endif
                    @if($product->demo_url)
                        <a href="{{ $product->demo_url }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">Live demo ↗</a>
                    @endif
                    @if($product->github_url)
                        <a href="{{ $product->github_url }}" target="_blank" rel="noopener" class="btn-ghost btn-sm">GitHub ↗</a>
                    @endif

                    @can('update', $product)
                        <a href="{{ route('products.edit', $product) }}" class="btn-ghost btn-sm">Edit</a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Main + Sidebar --}}
        <div class="product-body">

            <main class="product-main">

                {{-- Screenshot gallery --}}
                @if($product->screenshots->count())
                    <div class="screenshot-gallery">
                        <div class="screenshot-gallery__main" id="galleryMain">
                            <img src="{{ Storage::url($product->screenshots->first()->image_path) }}"
                                 alt="{{ $product->name }} screenshot" id="galleryMainImg">
                        </div>
                        @if($product->screenshots->count() > 1)
                            <div class="screenshot-gallery__thumbs">
                                @foreach($product->screenshots as $shot)
                                    <button class="screenshot-gallery__thumb @if($loop->first) active @endif"
                                            data-src="{{ Storage::url($shot->image_path) }}"
                                            aria-label="Screenshot {{ $loop->iteration }}">
                                        <img src="{{ Storage::url($shot->image_path) }}" alt="">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Description --}}
                <div class="product-description">
                    <h2>About {{ $product->name }}</h2>
                    <div class="product-description__body">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                {{-- Tags --}}
                @if($product->tags->count())
                    <div class="product-tags">
                        @foreach($product->tags as $tag)
                            <span class="product-tag">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Comments placeholder --}}
                <div class="product-section">
                    <h2>Discussion</h2>
                    <p class="text-muted">Comments coming soon.</p>
                </div>

                {{-- Build log placeholder --}}
                @if($product->is_roast_enabled)
                    <div class="product-section">
                        <h2>Roast Mode 🔥</h2>
                        <p class="text-muted">Roast feedback coming soon.</p>
                    </div>
                @endif

            </main>

            <aside class="product-sidebar">

                {{-- Maker card --}}
                <div class="sidebar-card">
                    <h3 class="sidebar-card__heading">Made by</h3>
                    <div class="maker-card">
                        <div class="maker-card__avatar">
                            @if($product->user->avatar)
                                <img src="{{ Storage::url($product->user->avatar) }}" alt="{{ $product->user->name }}">
                            @else
                                <span>{{ strtoupper(substr($product->user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="maker-card__info">
                            <strong>{{ $product->user->name }}</strong>
                            <span class="text-muted">@{{ $product->user->username }}</span>
                        </div>
                    </div>
                    @if($product->user->bio)
                        <p class="maker-card__bio">{{ $product->user->bio }}</p>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="sidebar-card">
                    <h3 class="sidebar-card__heading">Stats</h3>
                    <div class="product-stats">
                        <div class="product-stat">
                            <span class="product-stat__value">{{ number_format($product->views_count) }}</span>
                            <span class="product-stat__label">views</span>
                        </div>
                        <div class="product-stat">
                            <span class="product-stat__value">{{ $product->tags->count() }}</span>
                            <span class="product-stat__label">tags</span>
                        </div>
                    </div>
                </div>

            </aside>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const mainImg = document.getElementById('galleryMainImg');
    const thumbs  = document.querySelectorAll('.screenshot-gallery__thumb');

    thumbs.forEach(btn => {
        btn.addEventListener('click', () => {
            mainImg.src = btn.dataset.src;
            thumbs.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
}());
</script>
@endpush
