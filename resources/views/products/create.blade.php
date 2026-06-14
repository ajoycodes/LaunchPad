@extends('layouts.app')

@section('title', 'Submit a Product')

@section('content')
<div class="container-app">
    <div class="submit-wrap">

        <div class="submit-header">
            <h1 class="submit-header__title">Submit a Product</h1>
            <p class="submit-header__sub">Share your product with the LaunchPad community.</p>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- ── Section 1: Basic Info & Category ─────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Basic Info</h2>

                <div class="form-field">
                    <label for="name">Product name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           maxlength="100" placeholder="e.g. LaunchPad" required>
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="tagline">Tagline <span class="required">*</span></label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline') }}"
                           maxlength="120" placeholder="One sentence that describes what you built" required>
                    <span class="form-hint">Max 120 characters</span>
                    @error('tagline')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="description">Description <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="6"
                              maxlength="5000" placeholder="Tell the community what your product does, who it's for, and why you built it." required>{{ old('description') }}</textarea>
                    @error('description')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="category_id">Category <span class="required">*</span></label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select a category…</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- ── Section 2: Tags ──────────────────────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Tags <span class="form-hint">(pick up to 5)</span></h2>

                <div class="tag-grid">
                    @foreach($tags as $tag)
                        <label class="tag-option">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   @checked(in_array($tag->id, old('tags', [])))>
                            <span class="tag-option__label">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            {{-- ── Section 3: Screenshots ───────────────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Screenshots <span class="form-hint">(up to 5)</span></h2>

                <div class="screenshot-upload">
                    <label class="screenshot-upload__dropzone" for="screenshots" id="screenshotDropzone">
                        <span class="screenshot-upload__icon">🖼️</span>
                        <span>Click to add screenshots</span>
                        <span class="form-hint">PNG, JPG or WebP · max 4 MB each</span>
                        <input type="file" id="screenshots" name="screenshots[]"
                               accept="image/*" multiple style="display:none;" aria-label="Upload screenshots">
                    </label>
                    <div class="screenshot-preview-grid" id="screenshotPreviews"></div>
                </div>
                @error('screenshots')<span class="form-error">{{ $message }}</span>@enderror
                @error('screenshots.*')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            {{-- ── Section 5: Logo Upload ────────────────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Logo</h2>

                <div class="logo-upload">
                    <div class="logo-upload__preview" id="logoPreview">
                        <span class="logo-upload__placeholder">🚀</span>
                    </div>
                    <div class="logo-upload__controls">
                        <label class="btn-ghost btn-sm" for="logo" style="cursor:pointer;">
                            Choose image
                        </label>
                        <input type="file" id="logo" name="logo" accept="image/*"
                               style="display:none;" aria-label="Upload logo">
                        <span class="form-hint">PNG, JPG or WebP · max 2 MB · square works best</span>
                        @error('logo')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const checkboxes = document.querySelectorAll('input[name="tags[]"]');
    const max = 5;

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const checked = document.querySelectorAll('input[name="tags[]"]:checked');
            if (checked.length >= max) {
                checkboxes.forEach(c => { if (!c.checked) c.disabled = true; });
            } else {
                checkboxes.forEach(c => c.disabled = false);
            }
        });
    });
}());

// Screenshot upload previews
(function () {
    const input   = document.getElementById('screenshots');
    const grid    = document.getElementById('screenshotPreviews');
    const zone    = document.getElementById('screenshotDropzone');
    const max     = 5;

    if (!input || !grid) return;

    input.addEventListener('change', () => {
        grid.innerHTML = '';
        const files = Array.from(input.files).slice(0, max);

        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const thumb = document.createElement('div');
                thumb.className = 'screenshot-thumb';
                thumb.innerHTML = `<img src="${e.target.result}" alt="">`;
                grid.appendChild(thumb);
            };
            reader.readAsDataURL(file);
        });

        zone.querySelector('span:first-of-type').textContent =
            files.length ? `${files.length} image${files.length > 1 ? 's' : ''} selected` : 'Click to add screenshots';
    });
}());

// Logo preview
(function () {
    const input   = document.getElementById('logo');
    const preview = document.getElementById('logoPreview');

    if (!input || !preview) return;

    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}" alt="Logo preview">`;
        };
        reader.readAsDataURL(file);
    });
}());
</script>
@endpush
