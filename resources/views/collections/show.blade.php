@extends('layouts.app')

@section('title', $collection->name . ' — Collections — LaunchPad')

@section('content')
<div class="container-app">

    {{-- Collection header --}}
    <div class="collection-header">
        <div class="collection-header__main">
            <div class="collection-header__eyebrow">
                <a href="{{ route('collections.index') }}" class="text-muted">Collections</a>
                <span class="text-muted">/</span>
                <span>{{ $collection->name }}</span>
            </div>
            <h1 class="collection-header__title">{{ $collection->name }}</h1>
            @if($collection->description)
                <p class="collection-header__desc">{{ $collection->description }}</p>
            @endif
            <div class="collection-header__meta">
                <a href="{{ route('makers.show', $collection->user->username) }}" class="collection-header__curator">
                    <div class="collection-card__avatar">
                        <x-avatar :user="$collection->user" size="sm" />
                    </div>
                    <span>by {{ $collection->user->name }}</span>
                </a>
                <span class="text-muted">·</span>
                <span class="text-muted">{{ $collection->products->count() }} products</span>
                <span class="text-muted">·</span>
                <span class="collection-header__follower-count" id="followerCount">
                    {{ $collection->followers_count }} {{ Str::plural('follower', $collection->followers_count) }}
                </span>
            </div>
        </div>

        <div class="collection-header__actions">
            @auth
                @if(auth()->id() !== $collection->user_id)
                    <button class="btn-ghost follow-btn {{ $isFollowing ? 'following' : '' }}"
                            id="followBtn"
                            data-collection-id="{{ $collection->id }}"
                            data-auth="true">
                        <i data-lucide="{{ $isFollowing ? 'user-check' : 'user-plus' }}" class="icon-inline" id="followIcon"></i>
                        <span id="followLabel">{{ $isFollowing ? 'Following' : 'Follow' }}</span>
                    </button>
                @endif
                @if(auth()->id() === $collection->user_id)
                    <form method="POST" action="{{ route('collections.destroy', $collection) }}"
                          onsubmit="return confirm('Delete this collection?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-ghost btn-sm btn-danger-ghost">
                            <i data-lucide="trash-2" class="icon-inline"></i> Delete
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Follow</a>
            @endauth
        </div>
    </div>

    {{-- Product list --}}
    @if($collection->products->isEmpty())
        <div class="empty-state">
            <i data-lucide="bookmark" class="empty-state__icon"></i>
            <p>No products in this collection yet.</p>
            @if(auth()->id() === $collection->user_id)
                <a href="{{ route('home') }}" class="btn-accent btn-sm">Browse products to add</a>
            @endif
        </div>
    @else
        <div class="product-list">
            @foreach($collection->products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
(function () {
    const btn = document.getElementById('followBtn');
    if (!btn) return;

    btn.addEventListener('click', function () {
        const collectionId = btn.dataset.collectionId;
        btn.disabled = true;

        fetch(`/collections/${collectionId}/follow`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(res => res.json())
        .then(data => {
            const icon  = document.getElementById('followIcon');
            const label = document.getElementById('followLabel');
            const count = document.getElementById('followerCount');

            if (data.following) {
                btn.classList.add('following');
                icon.setAttribute('data-lucide', 'user-check');
                label.textContent = 'Following';
            } else {
                btn.classList.remove('following');
                icon.setAttribute('data-lucide', 'user-plus');
                label.textContent = 'Follow';
            }

            if (count) {
                count.textContent = data.count + (data.count === 1 ? ' follower' : ' followers');
            }

            lucide.createIcons();
        })
        .finally(() => { btn.disabled = false; });
    });
}());
</script>
@endpush
