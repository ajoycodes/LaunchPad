@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')

@if(session('success'))
    <div class="flash flash--success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash--error">{{ session('error') }}</div>
@endif

<div class="admin-categories-grid">

    {{-- Create new category --}}
    <div class="admin-category-card admin-category-card--new">
        <h3 class="admin-category-card__title">New Category</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="admin-category-form">
            @csrf
            <div class="admin-category-form__fields">
                <input type="text"
                       name="name"
                       placeholder="Name"
                       required
                       class="admin-input"
                       value="{{ old('name') }}">
                <input type="text"
                       name="icon"
                       placeholder="Lucide icon (e.g. terminal)"
                       required
                       class="admin-input"
                       value="{{ old('icon') }}">
            </div>
            <button type="submit" class="admin-action-btn admin-action-btn--approve" style="align-self:flex-start;">
                <i data-lucide="plus"></i> Add Category
            </button>
        </form>
        @error('name')<p class="admin-error">{{ $message }}</p>@enderror
        @error('icon')<p class="admin-error">{{ $message }}</p>@enderror
    </div>

    {{-- Existing categories --}}
    @foreach($categories as $category)
        <div class="admin-category-card">
            <div class="admin-category-card__header">
                <i data-lucide="{{ $category->icon }}" class="admin-category-card__icon"></i>
                <span class="admin-category-card__name">{{ $category->name }}</span>
                <span class="admin-category-card__count">{{ $category->products_count }} products</span>
            </div>

            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="admin-category-form">
                @csrf
                @method('PUT')
                <div class="admin-category-form__fields">
                    <input type="text"
                           name="name"
                           value="{{ $category->name }}"
                           required
                           class="admin-input">
                    <input type="text"
                           name="icon"
                           value="{{ $category->icon }}"
                           required
                           class="admin-input">
                </div>
                <button type="submit" class="admin-action-btn admin-action-btn--feature">Save</button>
            </form>
            @if($category->products_count === 0)
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                      style="margin-top:6px;"
                      onsubmit="return confirm('Delete {{ addslashes($category->name) }}?')">
                    @csrf
                    @method('DELETE')
                    <button class="admin-action-btn admin-action-btn--reject">Delete</button>
                </form>
            @endif
        </div>
    @endforeach

</div>

@endsection
