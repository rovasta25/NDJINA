@extends('layouts.app')

@section('title', 'Alertes de sang')

@section('content')
    <div class="page-head">
        <div>
            <h1>Alertes en cours</h1>
            <p class="muted">Actualisées en direct. Le numéro des demandeurs reste masqué jusqu'à l'ouverture de l'alerte.</p>
        </div>
        <div class="live-pill"><span class="pulse-dot"></span> En direct</div>
    </div>

    <div class="filter-bar">
        <div class="filter-search">
            <x-icon name="search" class="icon-sm"/>
            <input type="text" id="f-q" placeholder="Rechercher un lieu, une ville…">
        </div>

        <div class="filter-select">
            <label class="sr-only" for="f-groupe">Groupe sanguin</label>
            <select id="f-groupe">
                <option value="">Tous les groupes</option>
                @foreach ($groupes as $groupe)
                    <option value="{{ $groupe->id }}">{{ $groupe->libelle }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-select">
            <label class="sr-only" for="f-urgence">Urgence</label>
            <select id="f-urgence">
                <option value="">Toute urgence</option>
                <option value="haute">Urgent</option>
                <option value="moyenne">Modéré</option>
                <option value="basse">Faible</option>
            </select>
        </div>

        <div class="filter-select">
            <label class="sr-only" for="f-periode">Période</label>
            <select id="f-periode">
                <option value="">Toutes les périodes</option>
                <option value="aujourdhui">Aujourd'hui</option>
                <option value="hier">Hier</option>
                <option value="semaine">Cette semaine</option>
                <option value="mois">Mois dernier</option>
            </select>
        </div>

        <div class="filter-select">
            <label class="sr-only" for="f-ville">Ville</label>
            <select id="f-ville">
                <option value="">Toutes les villes</option>
                @foreach ($villes as $ville)
                    <option value="{{ $ville }}">{{ $ville }}</option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('alertes.create') }}" class="btn btn-primary filter-publish">
            <x-icon name="plus" class="icon-sm"/> Publier une alerte
        </a>
    </div>

    <div class="alerts-list" id="alerts-list">
        <div class="empty-state">
            <x-icon name="refresh" class="icon-xl"/>
            <p>Chargement des alertes…</p>
        </div>
    </div>
@endsection

@section('scripts')
@php
    $labels = [
        'empty' => 'Aucune alerte ne correspond à ces critères pour le moment.',
        'poche' => 'poche',
        'poches' => 'poches',
        'reponse' => 'réponse',
        'reponses' => 'réponses',
        'voir' => "Voir l'alerte",
        'loading' => 'Chargement des alertes…',
    ];
@endphp
<script>
    window.alertesApi = @json(route('alertes.api'));
    window.alerteBase = @json(route('alertes.show', ['alerte' => '__ID__', 'from' => 'index']));
    window.labels = @json($labels);
</script>
@endsection
