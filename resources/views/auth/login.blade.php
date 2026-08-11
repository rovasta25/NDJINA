@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
    <div class="auth-page reveal">
        <div class="auth-brand">
            <div class="auth-brand-inner">
                <h2>Bon retour parmi nous.</h2>
                <p>Connectez-vous pour consulter les alertes, proposer votre aide et échanger avec les demandeurs.</p>
                <div class="auth-brand-points">
                    <span><x-icon name="checkc" class="icon-sm"/> Alertes en temps réel</span>
                    <span><x-icon name="checkc" class="icon-sm"/> Messagerie intégrée</span>
                    <span><x-icon name="checkc" class="icon-sm"/> Données protégées</span>
                </div>
            </div>
        </div>

        <div class="auth-form">
            <div class="auth-card">
                <h1>Connexion</h1>
                <p class="auth-subtitle">Accédez à votre espace NDJINA.</p>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Adresse e-mail</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="vous@exemple.gab" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="form-group form-inline">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" id="remember">
                            Se souvenir de moi
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                </form>

                <p class="auth-alt">Pas encore de compte ? <a href="{{ route('register') }}">Inscrivez-vous</a></p>
            </div>
        </div>
    </div>
@endsection
