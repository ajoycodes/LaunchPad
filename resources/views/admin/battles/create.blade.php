@extends('layouts.admin')

@section('title', 'Create Battle')
@section('page-title', 'Create Battle')

@section('content')

@if(session('success'))
    <div class="flash flash--success">{{ session('success') }}</div>
@endif

<div class="admin-form-card">
    <form method="POST" action="{{ route('admin.battles.store') }}" class="admin-form">
        @csrf

        <div class="admin-form__row">
            <div class="admin-form__group">
                <label class="admin-form__label">Product A</label>
                <select name="product_a_id" required class="admin-input admin-select">
                    <option value="">— select product —</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_a_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (by {{ $product->user->name }})
                        </option>
                    @endforeach
                </select>
                @error('product_a_id')<p class="admin-error">{{ $message }}</p>@enderror
            </div>

            <div class="admin-form__vs">VS</div>

            <div class="admin-form__group">
                <label class="admin-form__label">Product B</label>
                <select name="product_b_id" required class="admin-input admin-select">
                    <option value="">— select product —</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_b_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (by {{ $product->user->name }})
                        </option>
                    @endforeach
                </select>
                @error('product_b_id')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="admin-form__row">
            <div class="admin-form__group">
                <label class="admin-form__label">Starts at</label>
                <input type="datetime-local"
                       name="starts_at"
                       value="{{ old('starts_at') }}"
                       required
                       class="admin-input">
                @error('starts_at')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
            <div class="admin-form__group">
                <label class="admin-form__label">Ends at</label>
                <input type="datetime-local"
                       name="ends_at"
                       value="{{ old('ends_at') }}"
                       required
                       class="admin-input">
                @error('ends_at')<p class="admin-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="admin-form__footer">
            <button type="submit" class="admin-action-btn admin-action-btn--approve" style="padding:8px 20px; font-size:.875rem;">
                <i data-lucide="swords"></i> Launch Battle
            </button>
            <a href="{{ route('admin.dashboard') }}" class="admin-action-btn" style="padding:8px 16px; font-size:.875rem;">Cancel</a>
        </div>
    </form>
</div>

@endsection
