@extends('layouts.app')

@section('title', 'Maker vs Maker Battle — LaunchPad')

@section('content')
<div class="container-app">

    <div class="page-header">
        <div>
            <h1 class="page-header__title">
                <i data-lucide="swords" class="icon-inline"></i> Maker vs Maker
            </h1>
            <p class="page-header__sub">Vote for your favourite product. Battle ends in:</p>
        </div>
    </div>

    @if(!$battle)
        <div class="empty-state">
            <i data-lucide="swords" class="empty-state__icon"></i>
            <p>No active battle right now. Check back soon!</p>
        </div>
    @else
        {{-- Countdown --}}
        <div class="battle-countdown">
            <div class="countdown" data-launch="{{ $battle->ends_at->toIso8601String() }}">
                <span class="countdown__unit"><b class="countdown__num">--</b><small>d</small></span>
                <span class="countdown__sep">:</span>
                <span class="countdown__unit"><b class="countdown__num">--</b><small>h</small></span>
                <span class="countdown__sep">:</span>
                <span class="countdown__unit"><b class="countdown__num">--</b><small>m</small></span>
                <span class="countdown__sep">:</span>
                <span class="countdown__unit"><b class="countdown__num">--</b><small>s</small></span>
            </div>
        </div>

        {{-- Battle arena --}}
        <div class="battle-arena" id="battleArena" data-battle-id="{{ $battle->id }}">

            {{-- Product A --}}
            <div class="battle-card {{ $userVote && $userVote->voted_for === 'a' ? 'battle-card--voted' : '' }}" id="cardA">
                <div class="battle-card__logo">
                    @if($battle->productA->logo)
                        <img src="{{ Storage::url($battle->productA->logo) }}" alt="{{ $battle->productA->name }}">
                    @else
                        <i data-lucide="box"></i>
                    @endif
                </div>
                <h2 class="battle-card__name">
                    <a href="{{ route('products.show', $battle->productA) }}">{{ $battle->productA->name }}</a>
                </h2>
                <p class="battle-card__tagline">{{ $battle->productA->tagline }}</p>
                <p class="battle-card__maker text-muted">by {{ $battle->productA->user->name }}</p>

                <button class="btn-battle vote-btn"
                        data-side="a"
                        aria-label="Vote for {{ $battle->productA->name }}"
                        {{ !auth()->check() ? 'disabled' : '' }}>
                    <i data-lucide="chevron-up" class="icon-inline"></i>
                    <span class="vote-label">{{ $userVote && $userVote->voted_for === 'a' ? 'Voted' : 'Vote' }}</span>
                    <span class="vote-count-a">{{ $battle->votes_a }}</span>
                </button>
                <div class="battle-card__pct" id="pctDisplayA">
                    <span class="battle-pct-num">{{ $battle->percentA() }}%</span>
                    <div class="battle-pct-bar">
                        <div class="battle-pct-fill battle-pct-fill--a" style="width:{{ $battle->percentA() }}%"></div>
                    </div>
                </div>
            </div>

            {{-- VS divider + percentage bar --}}
            <div class="battle-vs">
                <span class="battle-vs__label">VS</span>
                <div class="battle-bar">
                    <div class="battle-bar__a" id="barA" style="width: {{ $battle->percentA() }}%">
                        <span class="battle-bar__pct" id="pctA">{{ $battle->percentA() }}%</span>
                    </div>
                    <div class="battle-bar__b" id="barB" style="width: {{ $battle->percentB() }}%">
                        <span class="battle-bar__pct battle-bar__pct--b" id="pctB">{{ $battle->percentB() }}%</span>
                    </div>
                </div>
                <div class="battle-totals text-muted">
                    <span id="totalVotes">{{ $battle->totalVotes() }}</span> total votes
                </div>
            </div>

            {{-- Product B --}}
            <div class="battle-card {{ $userVote && $userVote->voted_for === 'b' ? 'battle-card--voted' : '' }}" id="cardB">
                <div class="battle-card__logo">
                    @if($battle->productB->logo)
                        <img src="{{ Storage::url($battle->productB->logo) }}" alt="{{ $battle->productB->name }}">
                    @else
                        <i data-lucide="box"></i>
                    @endif
                </div>
                <h2 class="battle-card__name">
                    <a href="{{ route('products.show', $battle->productB) }}">{{ $battle->productB->name }}</a>
                </h2>
                <p class="battle-card__tagline">{{ $battle->productB->tagline }}</p>
                <p class="battle-card__maker text-muted">by {{ $battle->productB->user->name }}</p>

                <button class="btn-battle vote-btn"
                        data-side="b"
                        aria-label="Vote for {{ $battle->productB->name }}"
                        {{ !auth()->check() ? 'disabled' : '' }}>
                    <i data-lucide="chevron-up" class="icon-inline"></i>
                    <span class="vote-label">{{ $userVote && $userVote->voted_for === 'b' ? 'Voted' : 'Vote' }}</span>
                    <span class="vote-count-b">{{ $battle->votes_b }}</span>
                </button>
                <div class="battle-card__pct" id="pctDisplayB">
                    <span class="battle-pct-num">{{ $battle->percentB() }}%</span>
                    <div class="battle-pct-bar">
                        <div class="battle-pct-fill battle-pct-fill--b" style="width:{{ $battle->percentB() }}%"></div>
                    </div>
                </div>
            </div>

        </div>

        @guest
            <p class="text-muted" style="text-align:center; margin-top:var(--space-4);">
                <a href="{{ route('login') }}">Log in</a> to cast your vote.
            </p>
        @endguest

    @endif

    {{-- Previous battles --}}
    @if($previous->count())
        <div class="dashboard-section" style="margin-top:var(--space-8);">
            <h2 class="dashboard-section__title">Past Battles</h2>
            <div class="past-battles">
                @foreach($previous as $past)
                    @php
                        $winner = $past->votes_a >= $past->votes_b ? $past->productA : $past->productB;
                        $loser  = $past->votes_a >= $past->votes_b ? $past->productB : $past->productA;
                    @endphp
                    @php
                        $total      = $past->votes_a + $past->votes_b;
                        $winnerPct  = $total > 0 ? round(($past->votes_a >= $past->votes_b ? $past->votes_a : $past->votes_b) / $total * 100) : 50;
                        $loserPct   = 100 - $winnerPct;
                    @endphp
                    <div class="past-battle">
                        <span class="past-battle__winner">
                            <i data-lucide="trophy" class="icon-inline" style="color:#CA8A04;"></i>
                            {{ $winner->name }} ({{ $winnerPct }}%)
                        </span>
                        <span class="past-battle__loser text-muted">vs {{ $loser->name }} ({{ $loserPct }}%)</span>
                        <span class="text-muted" style="font-size:.75rem; margin-left:auto;">
                            {{ $past->ends_at->diffForHumans() }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
(function () {
    // Countdown (reuse same function as calendar)
    function pad(n) { return String(n).padStart(2, '0'); }
    function updateCountdowns() {
        document.querySelectorAll('.countdown').forEach(el => {
            const target = new Date(el.dataset.launch).getTime();
            const diff   = Math.max(0, target - Date.now());
            const nums   = el.querySelectorAll('.countdown__num');
            if (nums[0]) nums[0].textContent = pad(Math.floor(diff / 86400000));
            if (nums[1]) nums[1].textContent = pad(Math.floor((diff % 86400000) / 3600000));
            if (nums[2]) nums[2].textContent = pad(Math.floor((diff % 3600000) / 60000));
            if (nums[3]) nums[3].textContent = pad(Math.floor((diff % 60000) / 1000));
        });
    }
    updateCountdowns();
    setInterval(updateCountdowns, 1000);

    // Vote
    const arena = document.getElementById('battleArena');
    if (!arena) return;
    const battleId = arena.dataset.battleId;

    arena.querySelectorAll('.vote-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            btn.disabled = true;
            fetch(`/battles/${battleId}/vote`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ side: btn.dataset.side }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) return;
                // Update counts
                document.querySelectorAll('.vote-count-a').forEach(el => el.textContent = data.votes_a);
                document.querySelectorAll('.vote-count-b').forEach(el => el.textContent = data.votes_b);
                document.getElementById('pctA').textContent = data.percent_a + '%';
                document.getElementById('pctB').textContent = data.percent_b + '%';
                document.getElementById('barA').style.width  = data.percent_a + '%';
                document.getElementById('barB').style.width  = data.percent_b + '%';
                document.getElementById('totalVotes').textContent = data.votes_a + data.votes_b;
                // Toggle voted state
                document.getElementById('cardA').classList.toggle('battle-card--voted', btn.dataset.side === 'a');
                document.getElementById('cardB').classList.toggle('battle-card--voted', btn.dataset.side === 'b');
            })
            .finally(() => { btn.disabled = false; });
        });
    });
}());
</script>
@endpush
