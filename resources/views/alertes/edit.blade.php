@extends('layouts.app')

@section('title', 'Modifier l\'alerte')

@section('content')
    @php $from = request('from'); @endphp
    <a href="{{ route('alertes.show', ['alerte' => $alerte, 'from' => $from]) }}" class="back-link">
        <x-icon name="arrow" class="icon-sm"/> Retour à l'alerte
    </a>

    <div class="form-card">
        <h1>Modifier l'alerte</h1>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('alertes.update', $alerte) }}">
            @csrf
            @method('PUT')
            @if ($from)
                <input type="hidden" name="from" value="{{ $from }}">
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label for="groupe_sanguin_id">Groupe sanguin recherché</label>
                    <select id="groupe_sanguin_id" name="groupe_sanguin_id" required>
                        <option value="">— Choisir le groupe —</option>
                        @foreach ($groupes as $groupe)
                            <option value="{{ $groupe->id }}" {{ old('groupe_sanguin_id', $alerte->groupe_sanguin_id) == $groupe->id ? 'selected' : '' }}>{{ $groupe->libelle }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantite">Nombre de poches</label>
                    <input type="number" id="quantite" name="quantite" value="{{ old('quantite', $alerte->quantite) }}" min="1" max="100" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="urgence">Niveau d'urgence</label>
                    <select id="urgence" name="urgence" required>
                        <option value="haute" {{ old('urgence', $alerte->urgence) === 'haute' ? 'selected' : '' }}>Urgent — vie en danger</option>
                        <option value="moyenne" {{ old('urgence', $alerte->urgence) === 'moyenne' ? 'selected' : '' }}>Modéré</option>
                        <option value="basse" {{ old('urgence', $alerte->urgence) === 'basse' ? 'selected' : '' }}>Faible — planifié</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" value="{{ old('ville', $alerte->ville) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="lieu">Lieu précis</label>
                <input type="text" id="lieu" name="lieu" value="{{ old('lieu', $alerte->lieu) }}" placeholder="CHU de Libreville">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4">{{ old('description', $alerte->description) }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('alertes.show', ['alerte' => $alerte, 'from' => $from]) }}" class="btn btn-ghost">Annuler</a>
                <button type="submit" class="btn btn-primary">
                    <x-icon name="check" class="icon-sm"/> Enregistrer
                </button>
            </div>
        </form>
    </div>
@endsection
