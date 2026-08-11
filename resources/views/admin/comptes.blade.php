@extends('layouts.app')

@section('title', 'Administration — Comptes')

@section('content')
    <div class="page-head">
        <div>
            <h1>Gestion des comptes</h1>
            <p class="muted">Gérer les utilisateurs et leurs rôles.</p>
        </div>
        <a href="{{ route('admin.index') }}" class="btn btn-ghost">Retour</a>
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
                        <td><strong>{{ $u->name }}</strong></td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.comptes.type', $u) }}" class="inline-form type-form">
                                @csrf
                                @method('PUT')
                                <select name="type" onchange="this.form.submit()">
                                    <option value="particulier" {{ $u->type === 'particulier' ? 'selected' : '' }}>Particulier</option>
                                    <option value="structure" {{ $u->type === 'structure' ? 'selected' : '' }}>Structure</option>
                                    <option value="admin" {{ $u->type === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
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
