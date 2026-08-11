@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    @php
        $isAdmin = $user->isAdmin();
    @endphp

    <div class="dash-hello">
        <div>
            <h1>Bonjour, {{ $user->name }}</h1>
            <p class="muted">
                @if ($isAdmin)
                    Compte administrateur · Vue globale de la plateforme
                @elseif ($user->isStructure())
                    {{ $user->structureTypeLabel() }} · {{ $user->city }}
                @else
                    Compte particulier · {{ $user->city }}
                @endif
            </p>
        </div>
        <div class="dash-actions">
            @if (!$isAdmin)
                <a href="{{ route('alertes.index') }}" class="btn btn-outline"><x-icon name="bell" class="icon-sm"/> Voir les alertes</a>
                <a href="{{ route('alertes.create') }}" class="btn btn-primary"><x-icon name="plus" class="icon-sm"/> Publier une alerte</a>
            @else
                <a href="{{ route('admin.index') }}" class="btn btn-primary"><x-icon name="shield" class="icon-sm"/> Administration</a>
            @endif
        </div>
    </div>

    @if ($isAdmin)
        <div class="stats-grid">
            <div class="stat-card"><span class="stat-icon stat-red"><x-icon name="bell" class="icon-lg"/></span><div><strong>{{ $stats['alertes_actives'] }}</strong><span class="muted">alertes actives</span></div></div>
            <div class="stat-card"><span class="stat-icon"><x-icon name="trend" class="icon-lg"/></span><div><strong>{{ $stats['total_alertes'] }}</strong><span class="muted">alertes au total</span></div></div>
            <div class="stat-card"><span class="stat-icon stat-blue"><x-icon name="users" class="icon-lg"/></span><div><strong>{{ $stats['total_users'] }}</strong><span class="muted">utilisateurs</span></div></div>
        </div>

        <div class="section-block">
            <div class="section-head"><h2>Dernières alertes</h2><a href="{{ route('admin.index') }}" class="btn btn-ghost btn-sm">Administration <x-icon name="arrow" class="icon-sm"/></a></div>
            <div class="alerts-mini">
                @forelse ($recentes as $alerte)
                        <a href="{{ route('alertes.show', ['alerte' => $alerte, 'from' => 'dashboard']) }}" class="mini-alert">
                        <span class="groupe-badge">{{ $alerte->groupeSanguin->libelle }}</span>
                        <span class="mini-alert-text">{{ $alerte->ville }} · {{ $alerte->quantite }} poche{{ $alerte->quantite > 1 ? 's' : '' }}</span>
                        <span class="muted mini-alert-time">{{ $alerte->created_at->diffForHumans() }}</span>
                    </a>
                @empty
                    <p class="muted">Aucune alerte récente.</p>
                @endforelse
            </div>
        </div>
    @else
        <div class="stats-grid">
            <a href="{{ route('alertes.mes') }}" class="stat-card stat-link">
                <span class="stat-icon stat-red"><x-icon name="bell" class="icon-lg"/></span>
                <div><strong>{{ $stats['mes_alertes'] }}</strong><span class="muted">mes alertes</span></div>
            </a>
            <div class="stat-card">
                <span class="stat-icon stat-blue"><x-icon name="pulse" class="icon-lg"/></span>
                <div><strong>{{ $stats['alertes_actives'] }}</strong><span class="muted">alertes actives</span></div>
            </div>
            <div class="stat-card">
                <span class="stat-icon stat-amber"><x-icon name="heart" class="icon-lg"/></span>
                <div><strong>{{ $stats['reponses_recues'] }}</strong><span class="muted">propositions reçues</span></div>
            </div>
            <div class="stat-card">
                <span class="stat-icon stat-green"><x-icon name="send" class="icon-lg"/></span>
                <div><strong>{{ $stats['mes_reponses'] }}</strong><span class="muted">aides proposées</span></div>
            </div>
            <a href="{{ route('messages.index') }}" class="stat-card stat-link">
                <span class="stat-icon"><x-icon name="message" class="icon-lg"/></span>
                <div><strong>{{ $stats['non_lus'] }}</strong><span class="muted">messages non lus</span></div>
            </a>
        </div>

        <div class="dash-grid-2">
            <div class="section-block">
                <div class="section-head"><h2>Alertes récentes à {{ $user->city }}</h2><a href="{{ route('alertes.index') }}" class="btn btn-ghost btn-sm">Tout voir <x-icon name="arrow" class="icon-sm"/></a></div>
                <div class="alerts-mini">
                    @forelse ($recentes as $alerte)
                        <a href="{{ route('alertes.show', ['alerte' => $alerte, 'from' => 'dashboard']) }}" class="mini-alert{{ $alerte->user_id === $user->id ? ' mine' : '' }}">
                            <span class="groupe-badge">{{ $alerte->groupeSanguin->libelle }}</span>
                            <span class="mini-alert-text">{{ $alerte->lieu ?? $alerte->ville }} · {{ $alerte->quantite }} poche{{ $alerte->quantite > 1 ? 's' : '' }}</span>
                            <span class="muted mini-alert-time">{{ $alerte->created_at->diffForHumans() }}</span>
                            @if ($alerte->user_id === $user->id)
                                <span class="mine-badge">Votre alerte</span>
                            @endif
                        </a>
                    @empty
                        <div class="empty-state small">
                            <x-icon name="checkc" class="icon-xl"/>
                            <p class="muted">Aucune alerte active dans votre ville pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="section-block">
                <div class="section-head"><h2>Mes dernières alertes</h2></div>
                <div class="alerts-mini">
                    @forelse ($mes_alertes_recentes as $alerte)
                    <a href="{{ route('alertes.show', ['alerte' => $alerte, 'from' => 'dashboard']) }}" class="mini-alert">
                            <span class="groupe-badge">{{ $alerte->groupeSanguin->libelle }}</span>
                            <span class="mini-alert-text">{{ $alerte->quantite }} poche{{ $alerte->quantite > 1 ? 's' : '' }} · {{ $alerte->reponses->count() }} proposition{{ $alerte->reponses->count() > 1 ? 's' : '' }}</span>
                            <span class="muted mini-alert-time">{{ $alerte->created_at->diffForHumans() }}</span>
                        </a>
                    @empty
                        <div class="empty-state small">
                            <x-icon name="bell" class="icon-xl"/>
                            <p class="muted">Aucune alerte publiée. La première peut sauver une vie.</p>
                            <a href="{{ route('alertes.create') }}" class="btn btn-primary btn-sm">Publier</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
@endsection
