@extends('layouts.app')

@section('title', 'Administration')

@section('content')
    <div class="page-head">
        <div>
            <h1>Administration</h1>
            <p class="muted">Vue d'ensemble de la plateforme NDJINA.</p>
        </div>
    </div>

    <div class="admin-nav">
        <a href="{{ route('admin.index') }}" class="admin-nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}"><x-icon name="trend" class="icon-sm"/> Vue d'ensemble</a>
        <a href="{{ route('admin.alertes') }}" class="admin-nav-link {{ request()->routeIs('admin.alertes') ? 'active' : '' }}"><x-icon name="bell" class="icon-sm"/> Alertes</a>
        <a href="{{ route('admin.comptes') }}" class="admin-nav-link {{ request()->routeIs('admin.comptes') ? 'active' : '' }}"><x-icon name="users" class="icon-sm"/> Comptes</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-icon"><x-icon name="users" class="icon-lg"/></span>
            <div><strong>{{ $total_users }}</strong><span class="muted">utilisateurs</span></div>
        </div>
        <div class="stat-card">
            <span class="stat-icon stat-blue"><x-icon name="user" class="icon-lg"/></span>
            <div><strong>{{ $particuliers }}</strong><span class="muted">particuliers</span></div>
        </div>
        <div class="stat-card">
            <span class="stat-icon stat-green"><x-icon name="home" class="icon-lg"/></span>
            <div><strong>{{ $structures }}</strong><span class="muted">structures</span></div>
        </div>
        <div class="stat-card">
            <span class="stat-icon stat-red"><x-icon name="bell" class="icon-lg"/></span>
            <div><strong>{{ $alertes_actives }}</strong><span class="muted">alertes actives</span></div>
        </div>
        <div class="stat-card">
            <span class="stat-icon stat-amber"><x-icon name="heart" class="icon-lg"/></span>
            <div><strong>{{ $reponses }}</strong><span class="muted">propositions</span></div>
        </div>
    </div>

    <div class="section-block">
        <div class="section-head">
            <h2>Dernières alertes</h2>
            <a href="{{ route('admin.alertes') }}" class="btn btn-ghost btn-sm">Tout voir <x-icon name="arrow" class="icon-sm"/></a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Groupe</th>
                    <th>Demandeur</th>
                    <th>Ville</th>
                    <th>Urgence</th>
                    <th>Statut</th>
                    <th>Publiée</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dernieres_alertes as $alerte)
                    <tr>
                        <td><span class="groupe-badge">{{ $alerte->groupeSanguin->libelle }}</span></td>
                        <td>{{ $alerte->user->name }}</td>
                        <td>{{ $alerte->ville }}</td>
                        <td><span class="urgence-badge urgence-{{ $alerte->urgence }}">{{ $alerte->urgenceLabel() }}</span></td>
                        <td><span class="statut-badge">{{ $alerte->statut }}</span></td>
                        <td class="muted">{{ $alerte->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">Aucune alerte.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
