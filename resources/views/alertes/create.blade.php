@extends('layouts.app')

@section('title', 'Publier une alerte')

@section('content')
    <a href="{{ route('alertes.mes') }}" class="back-link">
        <x-icon name="arrow" class="icon-sm"/> Retour à mes alertes
    </a>

    <div class="form-card">
        <h1>Publier une alerte</h1>
        <p class="muted">Une demande précise et complète augmente vos chances de trouver un donneur compatible.</p>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('alertes.store') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="groupe_sanguin_id">Groupe sanguin recherché</label>
                    <select id="groupe_sanguin_id" name="groupe_sanguin_id" required>
                        <option value="">— Choisir le groupe —</option>
                        @foreach ($groupes as $groupe)
                            <option value="{{ $groupe->id }}" {{ old('groupe_sanguin_id') == $groupe->id ? 'selected' : '' }}>{{ $groupe->libelle }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantite">Nombre de poches</label>
                    <input type="number" id="quantite" name="quantite" value="{{ old('quantite', 1) }}" min="1" max="100" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="urgence">Niveau d'urgence</label>
                    <select id="urgence" name="urgence" required>
                        <option value="haute" {{ old('urgence') === 'haute' ? 'selected' : '' }}>Urgent — vie en danger</option>
                        <option value="moyenne" {{ old('urgence') === 'moyenne' ? 'selected' : '' }}>Modéré</option>
                        <option value="basse" {{ old('urgence') === 'basse' ? 'selected' : '' }}>Faible — planifié</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" value="{{ old('ville', auth()->user()->city) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="lieu">Lieu précis <span class="muted">(hôpital, clinique, domicile…)</span></label>
                <input type="text" id="lieu" name="lieu" value="{{ old('lieu') }}" placeholder="CHU de Libreville">
            </div>

            <div class="form-group">
                <label for="description">Description <span class="muted">(optionnel)</span></label>
                <textarea id="description" name="description" rows="4" placeholder="Contexte, date de l'intervention, instructions…">{{ old('description') }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('alertes.index') }}" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <x-icon name="send" class="icon-sm"/> Publier l'alerte
                </button>
            </div>
        </form>
    </div>
@endsection
