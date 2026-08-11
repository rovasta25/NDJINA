@extends('layouts.app')

@section('title', 'Support — Messages reçus')

@section('content')
    <div class="page-head">
        <div>
            <h1>Support</h1>
            <p class="muted">Messages envoyés par les utilisateurs.</p>
        </div>
    </div>

    <div class="admin-nav">
        <a href="{{ route('admin.index') }}" class="admin-nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}"><x-icon name="trend" class="icon-sm"/> Vue d'ensemble</a>
        <a href="{{ route('admin.alertes') }}" class="admin-nav-link {{ request()->routeIs('admin.alertes') ? 'active' : '' }}"><x-icon name="bell" class="icon-sm"/> Alertes</a>
        <a href="{{ route('admin.support') }}" class="admin-nav-link {{ request()->routeIs('admin.support*') ? 'active' : '' }}"><x-icon name="message" class="icon-sm"/> Support</a>
        <a href="{{ route('admin.comptes') }}" class="admin-nav-link {{ request()->routeIs('admin.comptes') ? 'active' : '' }}"><x-icon name="users" class="icon-sm"/> Comptes</a>
    </div>

    @forelse ($conversations as $conv)
        <a href="{{ route('admin.support.show', $conv['user']) }}" class="conv-item">
            <div class="conv-avatar">{{ strtoupper(mb_substr($conv['user']->name, 0, 1)) }}</div>
            <div class="conv-body">
                <div class="conv-head">
                    <strong>{{ $conv['user']->name }}</strong>
                    <span class="muted conv-time">{{ $conv['dernier_heure'] ?? 'Aucun message' }}</span>
                </div>
                <div class="conv-sub">
                    @if ($conv['objet'])
                        <span class="groupe-badge mini">{{ $conv['objet'] }}</span>
                    @endif
                    <span class="muted truncate">{{ $conv['dernier_message'] ?? 'Nouvelle conversation' }}</span>
                    @if ($conv['traite'])
                        <span class="unread-badge traite">Traité</span>
                    @endif
                    @if ($conv['unread'] > 0)
                        <span class="unread-badge">{{ $conv['unread'] }}</span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="empty-state">
            <x-icon name="message" class="icon-xl"/>
            <h3>Aucun message</h3>
            <p class="muted">Aucun utilisateur n'a contacté l'administration pour le moment.</p>
        </div>
    @endforelse
@endsection
