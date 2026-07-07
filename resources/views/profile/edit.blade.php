@extends('layouts.app')

@section('title', 'Edit Profile — LaunchPad')

@section('content')
<div class="container-app container-narrow">
    <div class="form-page">
        <div class="form-page__header">
            <h1 class="form-page__title">Profile Settings</h1>
            <p class="form-page__sub">Update your public profile information.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(! $user->hasVerifiedEmail())
            <div class="alert alert-warning">
                Your email address is unverified.
                <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-link-inline">Resend verification email</button>
                </form>
                @if(session('status') === 'verification-link-sent')
                    <span class="text-muted">A new link has been sent to your email address.</span>
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="submit-form">
            @csrf
            @method('PUT')

            {{-- Avatar --}}
            <div class="form-section">
                <h2 class="form-section__title">Avatar</h2>
                <div class="form-group">
                    <div class="avatar-upload">
                        <div class="avatar-upload__preview" id="avatarPreview">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" id="avatarImg">
                            @else
                                <span id="avatarInitial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                <img src="" alt="" id="avatarImg" style="display:none;">
                            @endif
                        </div>
                        <div class="avatar-upload__actions">
                            <label for="avatar" class="btn-ghost btn-sm" style="cursor:pointer;">
                                <i data-lucide="upload" class="icon-inline"></i> Upload photo
                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                   class="visually-hidden" aria-label="Upload avatar">
                            <span class="form-hint">JPG, PNG or GIF · max 2 MB</span>
                        </div>
                    </div>
                    @error('avatar')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Basic info --}}
            <div class="form-section">
                <h2 class="form-section__title">Basic Info</h2>

                <div class="form-group">
                    <label for="name" class="form-label">Display name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" maxlength="100" required>
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                    <span class="form-hint">Username cannot be changed.</span>
                </div>

                <div class="form-group">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea id="bio" name="bio" class="form-control @error('bio') is-invalid @enderror"
                              rows="3" maxlength="300"
                              placeholder="Tell the community about yourself…">{{ old('bio', $user->bio) }}</textarea>
                    <span class="form-hint">Max 300 characters.</span>
                    @error('bio')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Social links --}}
            <div class="form-section">
                <h2 class="form-section__title">Links</h2>

                <div class="form-group">
                    <label for="website" class="form-label">
                        <i data-lucide="globe" class="icon-inline"></i> Website
                    </label>
                    <input type="url" id="website" name="website"
                           class="form-control @error('website') is-invalid @enderror"
                           value="{{ old('website', $user->website) }}"
                           placeholder="https://yourwebsite.com">
                    @error('website')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="twitter" class="form-label">
                        <i data-lucide="twitter" class="icon-inline"></i> Twitter / X handle
                    </label>
                    <div class="input-group">
                        <span class="input-group__prefix">@</span>
                        <input type="text" id="twitter" name="twitter"
                               class="form-control @error('twitter') is-invalid @enderror"
                               value="{{ old('twitter', ltrim($user->twitter ?? '', '@')) }}"
                               placeholder="yourhandle" maxlength="50">
                    </div>
                    @error('twitter')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-accent">Save changes</button>
                <a href="{{ route('makers.show', $user->username) }}" class="btn-ghost">View profile</a>
            </div>

        </form>

        <div class="form-page__header form-page__header--divider">
            <h2>Change Password</h2>
            <p class="form-page__sub">Use a long, random password to keep your account secure.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="submit-form">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="current_password" class="form-label">Current password</label>
                <input type="password" id="current_password" name="current_password"
                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                       autocomplete="current-password">
                @error('current_password', 'updatePassword')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="new_password" class="form-label">New password</label>
                <input type="password" id="new_password" name="password"
                       class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                       autocomplete="new-password">
                @error('password', 'updatePassword')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="new_password_confirmation" class="form-label">Confirm new password</label>
                <input type="password" id="new_password_confirmation" name="password_confirmation"
                       class="form-control" autocomplete="new-password">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-accent">Update password</button>
            </div>
        </form>

        <div class="form-section form-section--danger">
            <h2 class="form-section__title">Danger Zone</h2>
            <p class="form-hint">Deleting your account removes your profile, products, and activity. This cannot be undone.</p>

            <form method="POST" action="{{ route('profile.destroy') }}" class="form-row-inline">
                @csrf
                @method('DELETE')

                <input type="password" name="password" placeholder="Confirm your password"
                       class="form-control" required>
                <button type="submit" class="btn-danger"
                        onclick="return confirm('Delete your account permanently? This cannot be undone.')">
                    Delete account
                </button>
            </form>
            @error('password', 'userDeletion')<span class="form-error">{{ $message }}</span>@enderror
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const input   = document.getElementById('avatar');
    const img     = document.getElementById('avatarImg');
    const initial = document.getElementById('avatarInitial');

    if (!input) return;

    input.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            img.src = e.target.result;
            img.style.display = 'block';
            if (initial) initial.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
}());
</script>
@endpush
