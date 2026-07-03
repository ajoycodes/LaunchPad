@extends('layouts.app')

@section('title', 'Launch Calendar — LaunchPad')

@section('content')
<div class="container-app">
    <div class="page-header">
        <div>
            <h1 class="page-header__title">Launch Calendar</h1>
            <p class="page-header__sub">Products scheduled to launch soon.</p>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="empty-state">
            <i data-lucide="calendar" class="empty-state__icon"></i>
            <p>No upcoming launches scheduled yet.</p>
            @auth
                @if(auth()->user()->isMaker() || auth()->user()->isAdmin())
                    <a href="{{ route('products.create') }}" class="btn-accent btn-sm">Schedule a launch</a>
                @endif
            @endauth
        </div>
    @else
        <div class="calendar-list">
            @foreach($products as $product)
                <div class="calendar-item" data-launch="{{ $product->launch_date->toIso8601String() }}">
                    <div class="calendar-item__logo">
                        <x-product-logo :product="$product" size="sm" />
                    </div>

                    <div class="calendar-item__info">
                        <a href="{{ route('products.show', $product) }}" class="calendar-item__name">{{ $product->name }}</a>
                        <p class="calendar-item__tagline">{{ $product->tagline }}</p>
                        <div class="calendar-item__meta">
                            <span class="calendar-item__category">
                                <i data-lucide="{{ $product->category->icon }}" class="icon-inline"></i>
                                {{ $product->category->name }}
                            </span>
                            <span class="text-muted">·</span>
                            <a href="{{ route('makers.show', $product->user->username) }}" class="calendar-item__maker">
                                by {{ $product->user->name }}
                            </a>
                        </div>
                    </div>

                    <div class="calendar-item__right">
                        <div class="calendar-item__date">
                            <i data-lucide="calendar" class="icon-inline"></i>
                            {{ $product->launch_date->format('M j, Y') }}
                        </div>
                        <div class="countdown" data-launch="{{ $product->launch_date->toIso8601String() }}">
                            <span class="countdown__unit"><b class="countdown__num">--</b><small>d</small></span>
                            <span class="countdown__sep">:</span>
                            <span class="countdown__unit"><b class="countdown__num">--</b><small>h</small></span>
                            <span class="countdown__sep">:</span>
                            <span class="countdown__unit"><b class="countdown__num">--</b><small>m</small></span>
                            <span class="countdown__sep">:</span>
                            <span class="countdown__unit"><b class="countdown__num">--</b><small>s</small></span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    function pad(n) { return String(n).padStart(2, '0'); }

    function updateCountdowns() {
        document.querySelectorAll('.countdown').forEach(el => {
            const target = new Date(el.dataset.launch).getTime();
            const now    = Date.now();
            const diff   = Math.max(0, target - now);

            const days    = Math.floor(diff / 86400000);
            const hours   = Math.floor((diff % 86400000) / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);

            const nums = el.querySelectorAll('.countdown__num');
            if (nums[0]) nums[0].textContent = pad(days);
            if (nums[1]) nums[1].textContent = pad(hours);
            if (nums[2]) nums[2].textContent = pad(minutes);
            if (nums[3]) nums[3].textContent = pad(seconds);
        });
    }

    updateCountdowns();
    setInterval(updateCountdowns, 1000);
}());
</script>
@endpush
