@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')

<form method="GET" action="{{ route('admin.users') }}" class="admin-search-form">
    <div class="admin-search-wrap">
        <i data-lucide="search" class="admin-search-icon"></i>
        <input type="text"
               name="q"
               value="{{ $search }}"
               placeholder="Search by name, username, or email…"
               class="admin-search-input"
               autocomplete="off">
        @if($search)
            <a href="{{ route('admin.users') }}" class="admin-search-clear">
                <i data-lucide="x"></i>
            </a>
        @endif
    </div>
    <button type="submit" class="admin-action-btn admin-action-btn--feature">Search</button>
</form>

@if(session('success'))
    <div class="flash flash--success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash--error">{{ session('error') }}</div>
@endif

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Products</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr class="{{ $user->is_banned ? 'data-table__row--muted' : '' }}">
                    <td>
                        <a href="{{ route('makers.show', $user->username) }}" target="_blank" class="data-table__product-name">
                            {{ $user->name }}
                        </a>
                        <div style="font-size:.75rem; color:var(--color-text-muted);">@{{ $user->username }}</div>
                    </td>
                    <td style="font-size:.85rem;">{{ $user->email }}</td>
                    <td>
                        <span class="status-badge {{ $user->role === 'admin' ? 'status-badge--approved' : 'status-badge--pending' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->products_count }}</td>
                    <td>
                        @if($user->is_banned)
                            <span class="status-badge status-badge--rejected">Banned</span>
                        @else
                            <span class="status-badge status-badge--approved">Active</span>
                        @endif
                    </td>
                    <td style="font-size:.8rem; color:var(--color-text-muted);">{{ $user->created_at->format('M j, Y') }}</td>
                    <td>
                        <div class="data-table__actions">
                            <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                @csrf
                                <button class="admin-action-btn {{ $user->is_banned ? 'admin-action-btn--approve' : 'admin-action-btn--reject' }}">
                                    {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                </button>
                            </form>
                            @if($user->role !== 'admin')
                                <form method="POST" action="{{ route('admin.users.make-admin', $user) }}"
                                      onsubmit="return confirm('Make {{ addslashes($user->name) }} an admin?')">
                                    @csrf
                                    <button class="admin-action-btn admin-action-btn--feature">Make Admin</button>
                                </form>
                            @endif
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-action-btn admin-action-btn--reject">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:32px; color:var(--color-text-muted);">
                        No users found{{ $search ? " for "{$search}"" : '' }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $users->links() }}
</div>

@endsection
