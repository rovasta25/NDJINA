<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'NDJINA')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="blank-layout">
    @include('partials.icons')

    <header class="site-header">
        <div class="container header-inner">
            <span class="brand">
                <span class="brand-logo"><x-icon name="droplet" class="icon-lg"/></span>
                <span class="brand-name">NDJINA</span>
            </span>
        </div>
    </header>

    <main class="blank-main">
        <div class="flash-wrap" id="flash-wrap">
            @if (session('success'))
                <div class="toast toast-success"><x-icon name="checkc" class="icon-sm"/> <span>{{ session('success') }}</span></div>
            @endif
            @if (session('error'))
                <div class="toast toast-error"><x-icon name="x" class="icon-sm"/> <span>{{ session('error') }}</span></div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col footer-col-brand">
                    <span class="footer-brand">
                        <span class="brand-logo"><x-icon name="droplet" class="icon-lg"/></span>
                        <span class="brand-name">NDJINA</span>
                    </span>
                    <p class="footer-text">Mettre en relation les demandeurs et les donneurs de sang, en temps réel.</p>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Site créé par</h4>
                    <div class="footer-creator">
                        <strong>MOUANDZA MIKOUMOU Rova Chrismi Richie</strong>
                        <span class="footer-creator-phone">+241 62 39 52 26 · +241 76 57 37 32</span>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>Copyright ©2026 NDJINA — Tous droits réservés.</span>
            </div>
        </div>
    </footer>

    @yield('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
