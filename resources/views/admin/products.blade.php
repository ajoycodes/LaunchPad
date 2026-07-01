@extends('layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')

<div class="admin-tabs">
    @foreach(['all','pending','approved','rejected','scheduled'] as $s)
        <a href="{{ route('admin.products', ['status' => $s]) }}"
           class="admin-tab {{ $status === $s ? 'active' : '' }}">
            {{ ucfirst($s) }}
            <span style="font-size:.7rem; opacity:.7;">({{ $counts[$s] }})</span>
        </a>
    @endforeach
</div>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Maker</th>
                <th>Category</th>
                <th>Status</th>
                <th>Upvotes</th>
                <th>Submitted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        <a href="{{ route('products.show', $product) }}" target="_blank" class="data-table__product-name">
                            {{ $product->name }}
                        </a>
                    </td>
                    <td>{{ $product->user->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td><span class="status-badge status-badge--{{ $product->status }}">{{ ucfirst($product->status) }}</span></td>
                    <td>{{ $product->upvotes_count }}</td>
                    <td style="font-size:.8rem; color:var(--color-text-muted);">{{ $product->created_at->format('M j, Y') }}</td>
                    <td>
                        <div class="data-table__actions">
                            @if($product->status !== 'approved')
                                <form method="POST" action="{{ route('admin.products.approve', $product) }}">
                                    @csrf
                                    <button class="admin-action-btn admin-action-btn--approve">Approve</button>
                                </form>
                            @endif
                            @if($product->status !== 'rejected')
                                <form method="POST" action="{{ route('admin.products.reject', $product) }}">
                                    @csrf
                                    <button class="admin-action-btn admin-action-btn--reject">Reject</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.products.feature', $product) }}">
                                @csrf
                                <button class="admin-action-btn admin-action-btn--feature">
                                    {{ $product->is_featured ? 'Unfeature' : 'Feature' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button class="admin-action-btn admin-action-btn--reject">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state" style="padding:40px 20px;">
                            <i data-lucide="check-circle" class="empty-state__icon"></i>
                            <p class="empty-state__title">All caught up</p>
                            <p class="empty-state__text">No products pending review.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $products->links() }}
</div>

@endsection
