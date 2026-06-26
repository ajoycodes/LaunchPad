@extends('layouts.app')

@section('title', $maker->name . ' (@' . $maker->username . ') — LaunchPad')

@section('content')
<div class="maker-profile">

    {{-- Cover + Avatar + Identity --}}
    <div class="maker-cover">
        <div class="container-app">
            <div class="maker-cover__inner">
                <div class="maker-cover__avatar">
                    @if($maker->avatar)
                        <img src="{{ Storage::url($maker->avatar) }}" alt="{{ $maker->name }}">
                    @else
                        <span>{{ strtoupper(substr($maker->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div class="maker-cover__info">
                    <h1 class="maker-cover__name">{{ $maker->name }}</h1>
                    <p class="maker-cover__username text-muted">@{{ $maker->username }}</p>
                    @if($maker->bio)
                        <p class="maker-cover__bio">{{ $maker->bio }}</p>
                    @endif
                    <div class="maker-cover__links">
                        @if($maker->website)
                            <a href="{{ $maker->website }}" target="_blank" rel="noopener" class="maker-link">
                                <i data-lucide="globe" class="icon-inline"></i> Website
                            </a>
                        @endif
                        @if($maker->twitter)
                            <a href="https://twitter.com/{{ ltrim($maker->twitter, '@') }}" target="_blank" rel="noopener" class="maker-link">
                                <i data-lucide="twitter" class="icon-inline"></i> @{{ ltrim($maker->twitter, '@') }}
                            </a>
                        @endif
                    </div>
                </div>
                @auth
                    @if(auth()->id() === $maker->id)
                        <a href="{{ route('profile.edit') }}" class="btn-ghost btn-sm maker-cover__edit">
                            <i data-lucide="settings" class="icon-inline"></i> Edit profile
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="container-app">
        <div class="maker-body">

            {{-- Stats row --}}
            <div class="maker-stats">
                <div class="maker-stat">
                    <span class="maker-stat__value">{{ $products->count() }}</span>
                    <span class="maker-stat__label">Products</span>
                </div>
                <div class="maker-stat">
                    <span class="maker-stat__value">{{ number_format($totalUpvotes) }}</span>
                    <span class="maker-stat__label">Total upvotes</span>
                </div>
                <div class="maker-stat">
                    <span class="maker-stat__value">{{ $maker->created_at->format('M Y') }}</span>
                    <span class="maker-stat__label">Member since</span>
                </div>
            </div>

            {{-- Badges --}}
            @if($badges->count())
                <div class="maker-badges">
                    <h2 class="maker-section-title">Badges</h2>
                    <div class="maker-badges__list">
                        @foreach($badges as $badge)
                            <div class="maker-badge" title="Earned {{ $badge->earned_at->format('M j, Y') }}">
                                <i data-lucide="{{ $badge->icon }}" class="maker-badge__icon"></i>
                                <span class="maker-badge__label">{{ $badge->label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Products grid --}}
            <div class="maker-products">
                <h2 class="maker-section-title">Products</h2>

                @if($products->isEmpty())
                    <p class="text-muted">No approved products yet.</p>
                @else
                    <div class="maker-products__grid">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
