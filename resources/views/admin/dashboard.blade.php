@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="admin-stats">
    <div class="admin-stat">
        <div class="admin-stat__label">Total Products</div>
        <div class="admin-stat__value">{{ number_format($totalProducts) }}</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__label">Pending Review</div>
        <div class="admin-stat__value" style="color:#EA580C;">{{ $pendingCount }}</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__label">Total Users</div>
        <div class="admin-stat__value">{{ number_format($totalUsers) }}</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__label">Upvotes Today</div>
        <div class="admin-stat__value" style="color:#16A34A;">{{ number_format($upvotesToday) }}</div>
    </div>
</div>

<div class="dashboard-charts">
    <div class="chart-card">
        <h2 class="chart-card__title">New signups (last 7 days)</h2>
        <canvas id="signupChart" height="120"></canvas>
    </div>
    <div class="chart-card">
        <h2 class="chart-card__title">Submissions (last 7 days)</h2>
        <canvas id="submissionChart" height="120"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/charts.js') }}"></script>
<script>
(function () {
    const labels = @json($chartDates);
    const opts   = { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } };

    new Chart(document.getElementById('signupChart'), {
        type: 'bar',
        data: { labels, datasets: [{ label: 'Signups', data: @json($signupCounts), backgroundColor: '#FF6154', borderRadius: 4 }] },
        options: opts,
    });

    new Chart(document.getElementById('submissionChart'), {
        type: 'bar',
        data: { labels, datasets: [{ label: 'Submissions', data: @json($submissionCounts), backgroundColor: '#6366F1', borderRadius: 4 }] },
        options: opts,
    });
}());
</script>
@endpush
