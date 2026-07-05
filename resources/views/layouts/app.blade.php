<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'LaunchPad'))</title>

    {{-- Inter from Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 (CDN) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Design tokens + app styles (mtime query busts stale browser caches) --}}
    <link href="{{ asset('css/tokens.css') }}?v={{ filemtime(public_path('css/tokens.css')) }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}" rel="stylesheet">

    @stack('head')
</head>
<body>
    @include('partials.navbar')

    <div class="flash-stack">
        @include('partials.flash')
    </div>

    <main class="site-main">
        @yield('content')
    </main>

    {{-- Auth pages set hide-footer so the form fits one viewport --}}
    @hasSection('hide-footer')
    @else
        @include('partials.footer')
    @endif

    {{-- Bootstrap 5 JS bundle (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Lucide icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    {{-- Upvote AJAX handler --}}
    <script src="{{ asset('js/upvote.js') }}"></script>

    @stack('scripts')

    <script>
    lucide.createIcons();
    (function () {
        document.querySelectorAll('[data-auto-dismiss]').forEach(function (el) {
            var t = setTimeout(function () { dismiss(el); }, 4000);
            el.querySelector('.flash-toast__close').addEventListener('click', function () {
                clearTimeout(t);
                dismiss(el);
            });
        });
        function dismiss(el) {
            el.classList.add('flash-toast--out');
            el.addEventListener('transitionend', function () { el.remove(); });
        }
    }());
    </script>
</body>
</html>
