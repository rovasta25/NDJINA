@extends('layouts.app')

@section('title', 'Administration — Comptes')

@section('content')
    <div class="page-head">
        <div>
            <h1>Gestion des comptes</h1>
            <p class="muted">Gérer les utilisateurs et leurs rôles.</p>
        </div>
        <div>
            <a href="{{ route('admin.comptes.create') }}" class="btn btn-primary"><x-icon name="plus" class="icon-sm"/> Créer un compte</a>
            <a href="{{ route('admin.index') }}" class="btn btn-ghost">Retour</a>
        </div>
    </div>

    <div class="filter-bar compact">
        <form method="GET" action="{{ route('admin.comptes') }}" class="filter-form">
            <div class="filter-select">
                <select name="type">
                    <option value="">Tous les types</option>
                    <option value="particulier" {{ request('type') === 'particulier' ? 'selected' : '' }}>Particulier</option>
                    <option value="structure" {{ request('type') === 'structure' ? 'selected' : '' }}>Structure</option>
                    <option value="admin" {{ request('type') === 'admin' ? 'selected' : '' }}>Administrateur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-outline">Filtrer</button>
        </form>
    </div>

    <div class="table-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>E-mail</th>
                    <th>Type</th>
                    <th>Ville</th>
                    <th>Alertes</th>
                    <th>Inscrit</th>
                    <th class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr>
                        <td>
                            <strong>{{ $u->name }}</strong>
                            @if ($u->isAdmin())
                                <span class="badge badge-admin"><x-icon name="shield" class="icon-sm"/> Admin</span>
                            @endif
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @if ($u->isAdmin())
                                <span class="badge badge-admin"><x-icon name="shield" class="icon-sm"/> Admin</span>
                            @else
                                <span class="badge">{{ $u->type === 'structure' ? 'Structure' : 'Particulier' }}</span>
                            @endif
                        </td>
                        <td>{{ $u->city }}</td>
                        <td>{{ $u->alertes_count }}</td>
                        <td class="muted">{{ $u->created_at->format('d/m/Y') }}</td>
                        <td class="td-actions">
                            <form method="POST" action="{{ route('admin.comptes.destroy', $u) }}" data-confirm="Supprimer définitivement le compte de {{ $u->name }} ?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-ghost btn-sm btn-danger-ghost" title="Supprimer"><x-icon name="trash" class="icon-sm"/></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">Aucun compte.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $users->links('pagination::custom') }}
    </div>
@endsection
