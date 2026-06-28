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
      <li><a href="{{ url('/launch-calendar') }}">Launch Calendar</a></li>
      <li><a href="{{ url('/collections') }}">Collections</a></li>
    </ul>

    {{-- Auth buttons (right) --}}
    <div class="site-nav__auth">
      @auth
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
