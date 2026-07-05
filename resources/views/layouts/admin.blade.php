<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — LaunchPad</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/tokens.css') }}?v={{ filemtime(public_path('css/tokens.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body class="admin-body">

<div class="admin-layout">

    {{-- Sidebar --}}
    <aside class="admin-sidebar">
        <a href="{{ route('home') }}" class="admin-sidebar__logo">
            <i data-lucide="rocket" class="admin-sidebar__logo-icon"></i>
            <span>LaunchPad</span>
        </a>
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}"
               class="admin-nav__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('admin.products') }}"
               class="admin-nav__link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <i data-lucide="box"></i> Products
            </a>
            <a href="{{ route('admin.users') }}"
               class="admin-nav__link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i data-lucide="users"></i> Users
            </a>
            <a href="{{ route('admin.categories') }}"
               class="admin-nav__link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <i data-lucide="tag"></i> Categories
            </a>
            <a href="{{ route('admin.battles.create') }}"
               class="admin-nav__link {{ request()->routeIs('admin.battles*') ? 'active' : '' }}">
                <i data-lucide="swords"></i> Battles
            </a>
        </nav>
        <div class="admin-sidebar__footer">
            <a href="{{ route('home') }}" class="admin-nav__link">
                <i data-lucide="arrow-left"></i> Back to site
            </a>
        </div>
    </aside>

    {{-- Main --}}
    <div class="admin-main">
        <header class="admin-topbar">
            <h1 class="admin-topbar__title">@yield('page-title', 'Admin')</h1>
            <div class="admin-topbar__user">
                <span class="text-muted" style="font-size:.85rem;">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-ghost btn-xs">Log out</button>
                </form>
            </div>
        </header>

        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>
@stack('scripts')
</body>
</html>
