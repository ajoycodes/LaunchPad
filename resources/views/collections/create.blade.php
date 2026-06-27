@extends('layouts.app')

@section('title', 'New Collection — LaunchPad')

@section('content')
<div class="container-app container-narrow">
    <div class="form-page">
        <div class="form-page__header">
            <h1 class="form-page__title">New Collection</h1>
            <p class="form-page__sub">Curate a list of products around a theme or topic.</p>
        </div>

        <form method="POST" action="{{ route('collections.store') }}" class="submit-form">
            @csrf

            <div class="form-section">
                <div class="form-group">
                    <label for="name" class="form-label">Collection name <span class="required">*</span></label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" maxlength="100" required
                           placeholder="e.g. Best Indie SaaS Tools">
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror"
                              maxlength="500"
                              placeholder="What is this collection about?">{{ old('description') }}</textarea>
                    <span class="form-hint">Max 500 characters.</span>
                    @error('description')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Visibility</label>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="is_public" value="1"
                                   {{ old('is_public', '1') === '1' ? 'checked' : '' }}>
                            <span>
                                <i data-lucide="globe" class="icon-inline"></i>
                                <strong>Public</strong> — anyone can view this collection
                            </span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="is_public" value="0"
                                   {{ old('is_public') === '0' ? 'checked' : '' }}>
                            <span>
                                <i data-lucide="lock" class="icon-inline"></i>
                                <strong>Private</strong> — only you can view this collection
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-accent">Create collection</button>
                <a href="{{ route('collections.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
