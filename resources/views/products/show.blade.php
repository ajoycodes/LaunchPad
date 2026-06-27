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

                @php
                    $isUpvoted = auth()->check() && $product->isUpvotedBy(auth()->user());
                    $count     = $product->upvotes_count ?? 0;
                @endphp

                <div class="product-header__actions">
                    <button class="upvote-btn btn-upvote {{ $isUpvoted ? 'upvoted' : '' }}"
                            data-product-id="{{ $product->id }}"
                            data-auth="{{ auth()->check() ? 'true' : 'false' }}"
                            aria-label="Upvote {{ $product->name }}"
                            aria-pressed="{{ $isUpvoted ? 'true' : 'false' }}">
                        <i data-lucide="chevron-up" class="icon-inline"></i>
                        <span class="upvote-count">{{ $count }}</span>
                        <span>{{ $count === 1 ? 'upvote' : 'upvotes' }}</span>
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

                    {{-- Save to Collection dropdown --}}
                    @auth
                        @php $userCollections = auth()->user()->collections; @endphp
                        <div class="save-dropdown" id="saveDropdown">
                            <button class="btn-ghost btn-sm save-dropdown__toggle" id="saveToggle"
                                    aria-expanded="false" aria-haspopup="true">
                                <i data-lucide="bookmark" class="icon-inline"></i> Save
                            </button>
                            <div class="save-dropdown__menu" id="saveMenu" role="menu" style="display:none;">
                                @if($userCollections->isEmpty())
                                    <a href="{{ route('collections.create') }}" class="save-dropdown__item">
                                        <i data-lucide="plus" class="icon-inline"></i> Create a collection
                                    </a>
                                @else
                                    @foreach($userCollections as $col)
                                        <button class="save-dropdown__item save-to-collection"
                                                data-collection-id="{{ $col->id }}"
                                                data-product-id="{{ $product->id }}">
                                            <i data-lucide="bookmark" class="icon-inline"></i>
                                            {{ $col->name }}
                                        </button>
                                    @endforeach
                                    <div class="save-dropdown__divider"></div>
                                    <a href="{{ route('collections.create') }}" class="save-dropdown__item">
                                        <i data-lucide="plus" class="icon-inline"></i> New collection
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endauth
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

                {{-- Comments --}}
                <div class="product-section" id="comments">
                    <h2>Discussion <span class="comment-count">({{ $comments->count() }})</span></h2>

                    @auth
                        <form method="POST" action="{{ route('comments.store', $product) }}" class="comment-form">
                            @csrf
                            <input type="hidden" name="is_roast" value="0">
                            <div class="comment-form__inner">
                                <div class="comment-form__avatar">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="">
                                    @else
                                        <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="comment-form__fields">
                                    <textarea name="body" rows="3" maxlength="1000"
                                              placeholder="Share your thoughts…" required>{{ old('body') }}</textarea>
                                    @error('body')<span class="form-error">{{ $message }}</span>@enderror
                                    <div class="comment-form__actions">
                                        <button type="submit" class="btn-accent btn-sm">Post comment</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <p class="text-muted" style="font-size:.9rem;">
                            <a href="{{ route('login') }}">Log in</a> to join the discussion.
                        </p>
                    @endauth

                    @if($comments->isEmpty())
                        <p class="text-muted" style="font-size:.9rem; margin-top:var(--space-4);">No comments yet. Be the first!</p>
                    @else
                        <div class="comment-list">
                            @foreach($comments as $comment)
                                @include('partials.comment', ['comment' => $comment, 'product' => $product])
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Roast thread --}}
                @if($product->is_roast_enabled)
                    <div class="product-section roast-section" id="roast">
                        <div class="roast-header">
                            <i data-lucide="flame" class="roast-header__icon"></i>
                            <div>
                                <h2 class="roast-header__title">Roast Mode</h2>
                                <p class="roast-header__sub">Brutally honest feedback only. No sugarcoating.</p>
                            </div>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('comments.store', $product) }}" class="comment-form">
                                @csrf
                                <input type="hidden" name="is_roast" value="1">
                                <div class="comment-form__inner">
                                    <div class="comment-form__avatar">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="">
                                        @else
                                            <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="comment-form__fields">
                                        <textarea name="body" rows="3" maxlength="1000"
                                                  placeholder="Give them your honest roast…" required></textarea>
                                        <div class="comment-form__actions">
                                            <button type="submit" class="btn-roast btn-sm">Roast it</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <p class="text-muted" style="font-size:.9rem;">
                                <a href="{{ route('login') }}">Log in</a> to roast this product.
                            </p>
                        @endauth

                        @if($roastComments->isEmpty())
                            <p class="text-muted" style="font-size:.9rem; margin-top:var(--space-4);">No roasts yet. Be the first to roast!</p>
                        @else
                            <div class="comment-list">
                                @foreach($roastComments as $comment)
                                    @include('partials.comment', ['comment' => $comment, 'product' => $product])
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

            </main>

            <aside class="product-sidebar">

                {{-- Maker card --}}
                <div class="sidebar-card">
                    <h3 class="sidebar-card__heading">Made by</h3>
                    <a href="{{ route('makers.show', $product->user->username) }}" class="maker-card maker-card--link">
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
                    </a>
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

// Inline reply form toggle
(function () {
    document.addEventListener('click', function (e) {
        // Open reply form
        const replyBtn = e.target.closest('.comment__reply-btn');
        if (replyBtn) {
            const id   = replyBtn.dataset.commentId;
            const form = document.getElementById('reply-form-' + id);
            if (!form) return;

            // Close any other open reply forms
            document.querySelectorAll('.comment__reply-form').forEach(f => {
                if (f !== form) f.style.display = 'none';
            });

            const isOpen = form.style.display !== 'none';
            form.style.display = isOpen ? 'none' : 'block';
            if (!isOpen) form.querySelector('textarea')?.focus();
            return;
        }

        // Cancel reply
        const cancelBtn = e.target.closest('.comment__cancel-reply');
        if (cancelBtn) {
            const id   = cancelBtn.dataset.commentId;
            const form = document.getElementById('reply-form-' + id);
            if (form) form.style.display = 'none';
        }
    });
}());

// Save to Collection dropdown
(function () {
    const toggle = document.getElementById('saveToggle');
    const menu   = document.getElementById('saveMenu');
    if (!toggle || !menu) return;

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = menu.style.display !== 'none';
        menu.style.display = isOpen ? 'none' : 'block';
        toggle.setAttribute('aria-expanded', !isOpen);
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#saveDropdown')) {
            menu.style.display = 'none';
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
}());
</script>
@endpush
