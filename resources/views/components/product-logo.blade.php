@props(['product', 'size' => 'md'])

@if($product->logo)
    <img src="{{ \Illuminate\Support\Facades\Storage::url($product->logo) }}" alt="{{ $product->name }}">
@else
    <span class="tile-initials tile-initials--{{ $size }}" style="--tile-bg: {{ $product->tileColor() }}" aria-hidden="true">{{ $product->tileInitials() }}</span>
@endif
