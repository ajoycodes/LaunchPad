@extends('layouts.app')

@section('title', 'Dashboard — LaunchPad')

@section('content')
<div class="container-app">

    {{-- Dashboard header --}}
    <div class="page-header">
        <div>
            <h1 class="page-header__title">Dashboard</h1>
            <p class="page-header__sub">Overview of your products and activity.</p>
        </div>
        @if(auth()->user()->isMaker() || auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn-accent">
                <i data-lucide="plus" class="icon-inline"></i> Submit product
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Stats row --}}
    <div class="dashboard-stats">
        <div class="dashboard-stat">
            <div class="dashboard-stat__icon" style="background:#EFF6FF; color:#2563EB;">
                <i data-lucide="eye"></i>
            </div>
            <div class="dashboard-stat__body">
                <span class="dashboard-stat__value">{{ number_format($totalViews) }}</span>
                <span class="dashboard-stat__label">Total Views</span>
            </div>
        </div>
        <div class="dashboard-stat">
            <div class="dashboard-stat__icon" style="background:#FFF7ED; color:#EA580C;">
                <i data-lucide="chevron-up"></i>
            </div>
            <div class="dashboard-stat__body">
                <span class="dashboard-stat__value">{{ number_format($totalUpvotes) }}</span>
                <span class="dashboard-stat__label">Total Upvotes</span>
            </div>
        </div>
        <div class="dashboard-stat">
            <div class="dashboard-stat__icon" style="background:#F0FDF4; color:#16A34A;">
                <i data-lucide="box"></i>
            </div>
            <div class="dashboard-stat__body">
                <span class="dashboard-stat__value">{{ $totalProducts }}</span>
                <span class="dashboard-stat__label">Products</span>
            </div>
        </div>
        <div class="dashboard-stat">
            <div class="dashboard-stat__icon" style="background:#FAF5FF; color:#9333EA;">
                <i data-lucide="message-circle"></i>
            </div>
            <div class="dashboard-stat__body">
                <span class="dashboard-stat__value">{{ number_format($totalComments) }}</span>
                <span class="dashboard-stat__label">Comments</span>
            </div>
        </div>
    </div>

    {{-- Charts row --}}
    @if($products->count())
        <div class="dashboard-charts">
            {{-- Upvote history line chart --}}
            <div class="chart-card">
                <h2 class="chart-card__title">Upvotes last 30 days</h2>
                <canvas id="upvoteChart" height="120"></canvas>
            </div>

            {{-- Views per product bar chart --}}
            <div class="chart-card">
                <h2 class="chart-card__title">Views per product</h2>
                <canvas id="viewsChart" height="120"></canvas>
            </div>
        </div>
    @endif

    {{-- Products table --}}
    <div class="dashboard-section">
        <h2 class="dashboard-section__title">Your Products</h2>

        @if($products->isEmpty())
            <div class="empty-state">
                <i data-lucide="box" class="empty-state__icon"></i>
                <p>No products yet.</p>
                @if(auth()->user()->isMaker() || auth()->user()->isAdmin())
                    <a href="{{ route('products.create') }}" class="btn-accent btn-sm">Submit your first product</a>
                @endif
            </div>
        @else
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Upvotes</th>
                            <th>Views</th>
                            <th>Launch date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="data-table__product-name">
                                        {{ $product->name }}
                                    </a>
                                    <span class="text-muted" style="font-size:.8rem; display:block;">{{ $product->tagline }}</span>
                                </td>
                                <td>
                                    <span class="status-badge status-badge--{{ $product->status }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td>{{ number_format($product->upvotes_count) }}</td>
                                <td>{{ number_format($product->views_count) }}</td>
                                <td>{{ $product->launch_date?->format('M j, Y') ?? '—' }}</td>
                                <td>
                                    <div class="data-table__actions">
                                        <a href="{{ route('products.show', $product) }}" class="btn-ghost btn-xs">View</a>
                                        <a href="{{ route('products.edit', $product) }}" class="btn-ghost btn-xs">Edit</a>
                                        <button class="btn-ghost btn-xs add-update-btn"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}">
                                            Update
                                        </button>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}"
                                              onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-ghost btn-xs btn-danger-ghost">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

{{-- Add Update modal --}}
<div class="modal-overlay" id="updateModal" style="display:none;" aria-modal="true" role="dialog">
    <div class="modal">
        <div class="modal__header">
            <h2 class="modal__title">Add Build Update</h2>
            <button class="modal__close" id="closeModal" aria-label="Close">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('dashboard.updates.store') }}" class="modal__body">
            @csrf
            <input type="hidden" name="product_id" id="updateProductId">
            <p class="text-muted modal__product-name" id="updateProductName" style="font-size:.875rem; margin-bottom:var(--space-3);"></p>
            <div class="form-group">
                <label for="updateBody" class="form-label">What's new?</label>
                <textarea id="updateBody" name="body" rows="4" class="form-control"
                          maxlength="1000" placeholder="Share a progress update, new feature, or milestone…" required></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-accent">Post update</button>
                <button type="button" class="btn-ghost" id="cancelModal">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    // Upvote history line chart
    const upvoteCtx = document.getElementById('upvoteChart');
    if (upvoteCtx) {
        new Chart(upvoteCtx, {
            type: 'line',
            data: {
                labels: @json($upvoteDates),
                datasets: [{
                    label: 'Upvotes',
                    data: @json($upvoteCounts),
                    borderColor: '#FF6154',
                    backgroundColor: 'rgba(255,97,84,0.08)',
                    borderWidth: 2,
                    pointRadius: 3,
                    tension: 0.3,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 10 } },
                },
            },
        });
    }

    // Views per product bar chart
    const viewsCtx = document.getElementById('viewsChart');
    if (viewsCtx) {
        const viewsData = @json($viewsData->values());
        new Chart(viewsCtx, {
            type: 'bar',
            data: {
                labels: viewsData.map(d => d.name),
                datasets: [{
                    label: 'Views',
                    data: viewsData.map(d => d.views),
                    backgroundColor: '#FF6154',
                    borderRadius: 4,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { maxRotation: 30 } },
                },
            },
        });
    }

    // Add Update modal
    const modal    = document.getElementById('updateModal');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelModal');

    document.querySelectorAll('.add-update-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('updateProductId').value = this.dataset.productId;
            document.getElementById('updateProductName').textContent = 'Product: ' + this.dataset.productName;
            document.getElementById('updateBody').value = '';
            modal.style.display = 'flex';
        });
    });

    function closeModal() { modal.style.display = 'none'; }
    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
}());
</script>
@endpush
