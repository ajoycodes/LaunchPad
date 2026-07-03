@props(['user', 'size' => 'md'])

@if($user->avatar)
    <img src="{{ \Illuminate\Support\Facades\Storage::url($user->avatar) }}" alt="{{ $user->name }}">
@else
    <span class="tile-initials tile-initials--{{ $size }}" style="--tile-bg: {{ $user->tileColor() }}" aria-hidden="true">{{ $user->tileInitials() }}</span>
@endif
