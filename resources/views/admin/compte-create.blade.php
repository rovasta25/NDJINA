@extends('layouts.app')

@section('title', 'Administration — Créer un compte')

@section('content')
    <div class="page-head">
        <div>
            <h1>Créer un compte</h1>
            <p class="muted">Créer un nouveau compte utilisateur ou administrateur.</p>
        </div>
        <a href="{{ route('admin.comptes') }}" class="btn btn-ghost">Retour</a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.comptes.store') }}" data-confirm="Créer ce compte ?">
            @csrf

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="name">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required minlength="8" placeholder="8 caractères minimum">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8">
            </div>

            <div class="form-group">
                <label for="type">Type de compte</label>
                <select id="type" name="type" required>
                    <option value="admin" {{ old('type') === 'admin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="particulier" {{ old('type') === 'particulier' ? 'selected' : '' }}>Particulier</option>
                    <option value="structure" {{ old('type') === 'structure' ? 'selected' : '' }}>Structure sanitaire</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Créer le compte</button>
                <a href="{{ route('admin.comptes') }}" class="btn btn-ghost">Annuler</a>
            </div>
        </form>
    </div>
@endsection
