@extends('layouts.app')

@section('title', 'Collections — LaunchPad')

@section('content')
<div class="container-app">
    <div class="page-header">
        <div>
            <h1 class="page-header__title">Collections</h1>
            <p class="page-header__sub">Curated lists of products by the community.</p>
        </div>
        @auth
            <a href="{{ route('collections.create') }}" class="btn-accent btn-sm">
                <i data-lucide="plus" class="icon-inline"></i> New collection
            </a>
        @endauth
    </div>

    @if($collections->isEmpty())
        <div class="empty-state">
            <i data-lucide="bookmark" class="empty-state__icon"></i>
            <p class="empty-state__title">No collections yet</p>
            <p class="empty-state__text">No collections yet. Create one to curate your favorites.</p>
            @auth
                <a href="{{ route('collections.create') }}" class="btn-accent btn-sm">Create a collection</a>
            @endauth
        </div>
    @else
        <div class="collection-grid">
            @foreach($collections as $collection)
                <a href="{{ route('collections.show', $collection->slug) }}" class="collection-card">
                    <div class="collection-card__header">
                        <h2 class="collection-card__name">{{ $collection->name }}</h2>
                        <span class="collection-card__count">{{ $collection->products_count }} products</span>
                    </div>
                    @if($collection->description)
                        <p class="collection-card__desc">{{ Str::limit($collection->description, 100) }}</p>
                    @endif
                    <div class="collection-card__footer">
                        <div class="collection-card__curator">
                            <div class="collection-card__avatar">
                                @if($collection->user->avatar)
                                    <img src="{{ Storage::url($collection->user->avatar) }}" alt="{{ $collection->user->name }}">
                                @else
                                    <span>{{ strtoupper(substr($collection->user->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <span>{{ $collection->user->name }}</span>
                        </div>
                        <span class="collection-card__followers">
                            <i data-lucide="users" class="icon-inline"></i>
                            {{ $collection->followers_count }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $collections->links() }}
        </div>
    @endif
</div>
@endsection
