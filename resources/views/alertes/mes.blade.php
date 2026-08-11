@extends('layouts.app')

@section('title', 'Mes alertes')

@section('content')
    <div class="page-head">
        <div>
            <h1>Mes alertes</h1>
            <p class="muted">Suivez vos demandes et les propositions de donneurs.</p>
        </div>
        <a href="{{ route('alertes.create') }}" class="btn btn-primary"><x-icon name="plus" class="icon-sm"/> Nouvelle alerte</a>
    </div>

    @forelse ($alertes as $alerte)
        <div class="alerte-card">
            <div class="alerte-card-main">
                <div class="alerte-badges">
                    <span class="groupe-badge">{{ $alerte->groupeSanguin->libelle }}</span>
                    <span class="urgence-badge urgence-{{ $alerte->urgence }}">{{ $alerte->urgenceLabel() }}</span>
                    @if ($alerte->statut !== 'active')
                        <span class="statut-badge">{{ $alerte->statut === 'terminee' ? 'Terminée' : 'Rejetée' }}</span>
                    @endif
                </div>
                <h3>{{ $alerte->quantite }} poche{{ $alerte->quantite > 1 ? 's' : '' }} de sang {{ $alerte->groupeSanguin->libelle }}</h3>
                <div class="alerte-meta">
                    <span><x-icon name="pin" class="icon-sm"/> {{ $alerte->ville }}</span>
                    <span><x-icon name="clock" class="icon-sm"/> {{ $alerte->created_at->diffForHumans() }}</span>
                    <span><x-icon name="users" class="icon-sm"/> {{ $alerte->reponses->count() }} proposition{{ $alerte->reponses->count() > 1 ? 's' : '' }}</span>
                </div>
            </div>
            <div class="alerte-card-actions">
                <a href="{{ route('alertes.show', ['alerte' => $alerte, 'from' => 'mes']) }}" class="btn btn-outline"><x-icon name="eye" class="icon-sm"/> Voir</a>
                @if ($alerte->statut === 'active')
                    <form method="POST" action="{{ route('alertes.statut', $alerte) }}" data-confirm="Clôturer cette alerte ? Le besoin est-il satisfait ?">
                        @csrf
                        <input type="hidden" name="statut" value="terminee">
                        <button type="submit" class="btn btn-ghost" title="Clôturer"><x-icon name="check" class="icon-sm"/> Clôturer</button>
                    </form>
                @elseif ($alerte->statut === 'terminee')
                    <form method="POST" action="{{ route('alertes.statut', $alerte) }}" data-confirm="Réactiver cette alerte ? Elle redeviendra visible par les donneurs.">
                        @csrf
                        <input type="hidden" name="statut" value="active">
                        <button type="submit" class="btn btn-ghost" title="Réactiver"><x-icon name="refresh" class="icon-sm"/> Réactiver</button>
                    </form>
                @endif
                <a href="{{ route('alertes.edit', ['alerte' => $alerte, 'from' => 'mes']) }}" class="btn btn-ghost"><x-icon name="edit" class="icon-sm"/></a>
                <form method="POST" action="{{ route('alertes.destroy', $alerte) }}" data-confirm="Supprimer définitivement cette alerte ?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-danger-ghost"><x-icon name="trash" class="icon-sm"/></button>
                </form>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <x-icon name="bell" class="icon-xl"/>
            <h3>Aucune alerte publiée</h3>
            <p class="muted">Votre première alerte pourrait sauver une vie.</p>
            <a href="{{ route('alertes.create') }}" class="btn btn-primary">Publier ma première alerte</a>
        </div>
    @endforelse
@endsection
