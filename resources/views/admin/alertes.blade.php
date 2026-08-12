@extends('layouts.app')

@section('title', 'Administration — Alertes')

@section('content')
    <div class="page-head">
        <div>
            <h1>Gestion des alertes</h1>
            <p class="muted">Modérer et administrer les alertes de la plateforme.</p>
        </div>
        <a href="{{ route('admin.index') }}" class="btn btn-ghost">Retour</a>
    </div>

    <div class="filter-bar compact">
        <form method="GET" action="{{ route('admin.alertes') }}" class="filter-form">
            <div class="filter-select">
                <select name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('statut') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="terminee" {{ request('statut') === 'terminee' ? 'selected' : '' }}>Terminée</option>
                    <option value="rejetee" {{ request('statut') === 'rejetee' ? 'selected' : '' }}>Rejetée</option>
                </select>
            </div>
            <button type="submit" class="btn btn-outline">Filtrer</button>
        </form>
    </div>

    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Groupe</th>
                    <th>Demandeur</th>
                    <th>Ville</th>
                    <th>Quantité</th>
                    <th>Urgence</th>
                    <th>Statut</th>
                    <th class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alertes as $alerte)
                    <tr>
                        <td><span class="groupe-badge">{{ $alerte->groupeSanguin->libelle }}</span></td>
                        <td>{{ $alerte->user->name }}</td>
                        <td>{{ $alerte->ville }}</td>
                        <td>{{ $alerte->quantite }}</td>
                        <td><span class="urgence-badge urgence-{{ $alerte->urgence }}">{{ $alerte->urgenceLabel() }}</span></td>
                        <td><span class="statut-badge">{{ $alerte->statut }}</span></td>
                        <td class="td-actions">
                            <a href="{{ route('alertes.show', ['alerte' => $alerte, 'from' => 'admin']) }}" class="btn btn-ghost btn-sm" title="Voir"><x-icon name="eye" class="icon-sm"/></a>
                            @if ($alerte->statut === 'active')
                                <form method="POST" action="{{ route('admin.alertes.statut', $alerte) }}" data-confirm="Marquer cette alerte comme terminée ?">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="terminee">
                                    <button class="btn btn-ghost btn-sm btn-success-ghost" title="Terminer"><x-icon name="check" class="icon-sm"/></button>
                                </form>
                                <form method="POST" action="{{ route('admin.alertes.statut', $alerte) }}" data-confirm="Rejeter cette alerte ?">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="rejetee">
                                    <button class="btn btn-ghost btn-sm btn-danger-ghost" title="Rejeter"><x-icon name="x" class="icon-sm"/></button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.alertes.statut', $alerte) }}" data-confirm="Réactiver cette alerte ? Elle redeviendra visible par les donneurs.">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="statut" value="active">
                                    <button class="btn btn-ghost btn-sm" title="Réactiver"><x-icon name="refresh" class="icon-sm"/></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">Aucune alerte.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $alertes->links('pagination::custom') }}
    </div>
@endsection
