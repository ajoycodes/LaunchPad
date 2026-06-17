@extends('layouts.app')

@section('title', 'Edit ' . $product->name)

@section('content')
<div class="container-app">
    <div class="submit-wrap">

        <div class="submit-header">
            <h1 class="submit-header__title">Edit Product</h1>
            <p class="submit-header__sub">Update the details for <strong>{{ $product->name }}</strong>.</p>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            {{-- ── Section 1: Basic Info & Category ─────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Basic Info</h2>

                <div class="form-field">
                    <label for="name">Product name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                           maxlength="100" required>
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="tagline">Tagline <span class="required">*</span></label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $product->tagline) }}"
                           maxlength="120" required>
                    <span class="form-hint">Max 120 characters</span>
                    @error('tagline')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="description">Description <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="6" maxlength="5000" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="category_id">Category <span class="required">*</span></label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select a category…</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- ── Section 2: Tags ──────────────────────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Tags <span class="form-hint">(pick up to 5)</span></h2>

                @php $selectedTags = old('tags', $product->tags->pluck('id')->toArray()); @endphp
                <div class="tag-grid">
                    @foreach($tags as $tag)
                        <label class="tag-option">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   @checked(in_array($tag->id, $selectedTags))>
                            <span class="tag-option__label">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('tags')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            {{-- ── Section 3: Screenshots ───────────────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Screenshots <span class="form-hint">(up to 5 · upload replaces existing)</span></h2>

                @if($product->screenshots->count())
                    <div class="screenshot-preview-grid" style="margin-bottom:var(--space-3);">
                        @foreach($product->screenshots as $shot)
                            <div class="screenshot-thumb">
                                <img src="{{ Storage::url($shot->image_path) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="screenshot-upload">
                    <label class="screenshot-upload__dropzone" for="screenshots" id="screenshotDropzone">
                        <i data-lucide="image-plus" class="screenshot-upload__icon"></i>
                        <span>Click to add more screenshots</span>
                        <span class="form-hint">PNG, JPG or WebP · max 4 MB each</span>
                        <input type="file" id="screenshots" name="screenshots[]"
                               accept="image/*" multiple style="display:none;" aria-label="Upload screenshots">
                    </label>
                    <div class="screenshot-preview-grid" id="screenshotPreviews"></div>
                </div>
                @error('screenshots.*')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            {{-- ── Section 4: Logo Upload ────────────────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Logo</h2>

                <div class="logo-upload">
                    <div class="logo-upload__preview" id="logoPreview">
                        @if($product->logo)
                            <img src="{{ Storage::url($product->logo) }}" alt="Current logo">
                        @else
                            <i data-lucide="image" class="logo-upload__placeholder"></i>
                        @endif
                    </div>
                    <div class="logo-upload__controls">
                        <label class="btn-ghost btn-sm" for="logo" style="cursor:pointer;">
                            Replace image
                        </label>
                        <input type="file" id="logo" name="logo" accept="image/*"
                               style="display:none;" aria-label="Upload logo">
                        <span class="form-hint">PNG, JPG or WebP · max 2 MB · square works best</span>
                        @error('logo')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- ── Section 5: External Links ─────────────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Links <span class="form-hint">(all optional)</span></h2>

                <div class="form-field">
                    <label for="website_url">Website</label>
                    <input type="url" id="website_url" name="website_url"
                           value="{{ old('website_url', $product->website_url) }}" placeholder="https://yourproduct.com">
                    @error('website_url')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="demo_url">Live demo</label>
                    <input type="url" id="demo_url" name="demo_url"
                           value="{{ old('demo_url', $product->demo_url) }}" placeholder="https://demo.yourproduct.com">
                    @error('demo_url')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-field">
                    <label for="github_url">GitHub repo</label>
                    <input type="url" id="github_url" name="github_url"
                           value="{{ old('github_url', $product->github_url) }}" placeholder="https://github.com/you/repo">
                    @error('github_url')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- ── Section 6: Launch & Settings ─────────────────────────────── --}}
            <div class="submit-card">
                <h2 class="submit-card__heading">Settings</h2>

                <div class="form-field">
                    <label class="toggle-switch">
                        <input type="hidden" name="is_roast_enabled" value="0">
                        <input type="checkbox" name="is_roast_enabled" value="1"
                               @checked(old('is_roast_enabled', $product->is_roast_enabled))>
                        <span class="toggle-switch__track"></span>
                        <span class="toggle-switch__label">
                            Enable Roast Mode
                            <small>Let the community give brutally honest feedback</small>
                        </span>
                    </label>
                </div>

                <input type="hidden" name="launch_type" value="now">
            </div>

            <div class="submit-actions">
                <a href="{{ route('products.show', $product) }}" class="btn-ghost">Cancel</a>

                @can('delete', $product)
                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Delete this product? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">Delete product</button>
                    </form>
                @endcan

                <button type="submit" class="btn-accent">Save changes</button>
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

    // trigger on load to honour pre-checked state
    const initChecked = document.querySelectorAll('input[name="tags[]"]:checked');
    if (initChecked.length >= max) {
        checkboxes.forEach(c => { if (!c.checked) c.disabled = true; });
    }
}());

(function () {
    const input   = document.getElementById('screenshots');
    const grid    = document.getElementById('screenshotPreviews');
    const zone    = document.getElementById('screenshotDropzone');

    if (!input || !grid) return;

    input.addEventListener('change', () => {
        grid.innerHTML = '';
        const files = Array.from(input.files).slice(0, 5);

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
            files.length ? `${files.length} new image${files.length > 1 ? 's' : ''} selected` : 'Click to add more screenshots';
    });
}());

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
