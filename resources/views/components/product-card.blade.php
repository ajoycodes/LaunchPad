@props(['product'])

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
        <button class="btn-upvote-card" disabled title="Upvoting coming soon">
            <i data-lucide="chevron-up"></i>
            <span>0</span>
        </button>
    </div>
</div>
