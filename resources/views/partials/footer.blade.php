<footer class="site-footer">
  <div class="container-app site-footer__inner">
    <div class="site-footer__brand">
      <span class="site-footer__logo">🚀 LaunchPad</span>
      <p class="site-footer__tagline">Where makers launch and the community decides what wins.</p>
    </div>

    <div class="site-footer__cols">
      <div class="site-footer__col">
        <h4>Discover</h4>
        <a href="{{ url('/') }}">Today</a>
        <a href="{{ url('/launch-calendar') }}">Launch Calendar</a>
        <a href="{{ url('/collections') }}">Collections</a>
      </div>
      <div class="site-footer__col">
        <h4>Makers</h4>
        <a href="{{ url('/products/create') }}">Submit a product</a>
        <a href="{{ url('/dashboard') }}">Dashboard</a>
        <a href="{{ url('/battles/current') }}">Maker Battle</a>
      </div>
      <div class="site-footer__col">
        <h4>Account</h4>
        <a href="{{ url('/login') }}">Log in</a>
        <a href="{{ url('/register') }}">Sign up</a>
      </div>
    </div>
  </div>

  <div class="container-app site-footer__bottom">
    <span>&copy; {{ date('Y') }} LaunchPad. Built by makers, for makers.</span>
  </div>
</footer>
