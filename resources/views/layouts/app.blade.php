<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NDJINA') — Plateforme de don de sang</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.icons')

    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-logo"><x-icon name="droplet" class="icon-lg"/></span>
                <span class="brand-name">NDJINA</span>
            </a>

            <button class="nav-toggle" id="nav-toggle" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>

            <nav class="main-nav" id="main-nav">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="{{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'active' : '' }}">{{ auth()->check() ? 'Dashboard' : 'Accueil' }}</a>
                @guest
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">À propos</a>
                @endguest
                @auth
                    @if (!auth()->user()->isAdmin())
                        <a href="{{ route('alertes.index') }}" class="{{ request()->routeIs('alertes.index', 'alertes.show') ? 'active' : '' }}">Alertes en cours</a>
                        <a href="{{ route('messages.index') }}" class="{{ request()->routeIs('messages.*') ? 'active' : '' }}">Messages</a>
                        <a href="{{ route('support.index') }}" class="{{ request()->routeIs('support.*') ? 'active' : '' }}">Contacter l'admin</a>
                    @endif
                @endauth
            </nav>

            <div class="header-actions">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.index') }}" class="btn btn-ghost">
                            <x-icon name="shield" class="icon-sm"/> Admin
                        </a>
                        <a href="{{ route('profile.show') }}" class="btn btn-ghost">{{ auth()->user()->name }}</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-ghost" title="Déconnexion"><x-icon name="logout" class="icon-sm"/></button>
                        </form>
                    @else
                        <a href="{{ route('profile.show') }}" class="btn btn-ghost">{{ auth()->user()->name }}</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-ghost" title="Déconnexion"><x-icon name="logout" class="icon-sm"/></button>
                        </form>
                        <a href="{{ route('alertes.create') }}" class="btn btn-primary">
                            <x-icon name="plus" class="icon-sm"/> Publier
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Connexion</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">S'inscrire</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container page-main">
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
                    <a href="{{ route('home') }}" class="footer-brand">
                        <span class="brand-logo"><x-icon name="droplet" class="icon-lg"/></span>
                        <span class="brand-name">NDJINA</span>
                    </a>
                    <p class="footer-text">Mettre en relation les demandeurs et les donneurs de sang, en temps réel.</p>
                </div>

                <div class="footer-col">
                    <h4 class="footer-heading">Navigation</h4>
                    <div class="footer-links">
                        <a href="{{ auth()->check() ? route('dashboard') : route('home') }}">{{ auth()->check() ? 'Dashboard' : 'Accueil' }}</a>
                        @auth
                            @if (!auth()->user()->isAdmin())
                                <a href="{{ route('alertes.index') }}">Alertes en cours</a>
                                <a href="{{ route('messages.index') }}">Messages</a>
                                <a href="{{ route('support.index') }}">Contacter l'admin</a>
                            @endif
                            <a href="{{ route('profile.show') }}">Profil</a>
                        @endauth
                        <a href="{{ route('about') }}">À propos</a>
                    </div>
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

    <div class="modal-overlay" id="confirm-modal" hidden>
        <div class="modal">
            <h3 id="confirm-title">Confirmer</h3>
            <p id="confirm-text">Êtes-vous sûr ?</p>
            <div class="modal-actions">
                <button class="btn btn-ghost" data-modal-close>Annuler</button>
                <button class="btn btn-primary btn-danger" id="confirm-ok">Confirmer</button>
            </div>
        </div>
    </div>

    @yield('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
