<nav class="site-nav">
  <div class="container-app site-nav__inner">
    {{-- Logo (left) --}}
    <a href="{{ url('/') }}" class="site-nav__logo">
      <i data-lucide="rocket" class="site-nav__logo-mark"></i>
      <span class="site-nav__logo-text">LaunchPad</span>
    </a>

    {{-- Nav links (center) --}}
    <ul class="site-nav__links">
      <li><a href="{{ url('/') }}">Home</a></li>
      <li><a href="{{ route('launch-calendar') }}">Launch Calendar</a></li>
      <li><a href="{{ route('battles.show') }}">Battle</a></li>
      <li><a href="{{ url('/collections') }}">Collections</a></li>
    </ul>

    {{-- Auth buttons (right) --}}
    <div class="site-nav__auth">
      @auth
        @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
        <a href="{{ route('notifications') }}" class="btn-ghost nav-bell" title="Notifications" id="navBell">
            <i data-lucide="bell" class="icon-inline"></i>
            <span class="nav-bell__badge" id="navBellBadge" @if($unreadCount === 0) style="display:none;" @endif>{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="btn-ghost">
            <i data-lucide="settings" class="icon-inline"></i> Settings
        </a>
        <a href="{{ route('dashboard') }}" class="btn-ghost">Dashboard</a>
        <form method="POST" action="{{ url('/logout') }}" class="d-inline">
          @csrf
          <button type="submit" class="btn-ghost">Log out</button>
        </form>
      @else
        <a href="{{ url('/login') }}" class="btn-ghost">Log in</a>
        <a href="{{ url('/register') }}" class="btn-accent">Sign up</a>
      @endauth
    </div>
  </div>
</nav>

@auth
    @push('scripts')
    <script>
    (function () {
        const badge = document.getElementById('navBellBadge');
        if (!badge) return;

        // Poll the API for a live unread count so the badge stays fresh
        // without a full page reload. Authenticated via the session
        // cookie (Sanctum stateful), same as any other page request.
        function refreshUnreadCount() {
            fetch('/api/notifications/unread-count', {
                headers: { 'Accept': 'application/json' },
            })
                .then(res => res.ok ? res.json() : null)
                .then(data => {
                    if (!data) return;
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = '';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(() => {});
        }

        setInterval(refreshUnreadCount, 30000);
    }());
    </script>
    @endpush
@endauth
