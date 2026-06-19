@props(['product'])

@php
    $isUpvoted = auth()->check() && $product->isUpvotedBy(auth()->user());
    $count     = $product->upvotes_count ?? 0;
@endphp

<div class="product-card">
    {{-- Invisible full-row link (sits behind content via z-index) --}}
    <a href="{{ route('products.show', $product) }}" class="product-card__row-link" aria-label="{{ $product->name }}"></a>

    {{-- Logo --}}
    <div class="product-card__logo">
        @if($product->logo)
            <img src="{{ Storage::url($product->logo) }}" alt="{{ $product->name }}">
        @else
            <i data-lucide="box" class="product-card__logo-icon"></i>
        @endif
    </div>

    {{-- Body --}}
    <div class="product-card__body">
        <div class="product-card__name">{{ $product->name }}</div>
        <div class="product-card__tagline">{{ $product->tagline }}</div>
        @if($product->tags->count())
            <div class="product-card__tags">
                @foreach($product->tags->take(3) as $tag)
                    <span class="product-card__tag">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Upvote (sits above row link via z-index) --}}
    <div class="product-card__upvote">
        <button class="upvote-btn btn-upvote-card {{ $isUpvoted ? 'upvoted' : '' }}"
                data-product-id="{{ $product->id }}"
                data-auth="{{ auth()->check() ? 'true' : 'false' }}"
                aria-label="Upvote {{ $product->name }}"
                aria-pressed="{{ $isUpvoted ? 'true' : 'false' }}">
            <i data-lucide="chevron-up"></i>
            <span class="upvote-count">{{ $count }}</span>
        </button>
    </div>
</div>
