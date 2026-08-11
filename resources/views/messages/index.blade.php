@extends('layouts.app')

@section('title', 'Messages')

@section('content')
    <div class="page-head">
        <div>
            <h1>Messages</h1>
            <p class="muted">Vos échanges autour des alertes.</p>
        </div>
    </div>

    @forelse ($conversations as $conv)
        <a href="{{ route('messages.show', [$conv['alerte'], $conv['autre']]) }}" class="conv-item">
            <div class="conv-avatar">{{ strtoupper(mb_substr($conv['autre']->name, 0, 1)) }}</div>
            <div class="conv-body">
                <div class="conv-head">
                    <strong>{{ $conv['autre']->name }}</strong>
                    <span class="muted conv-time">{{ $conv['dernier_heure'] ?? 'Aucun message' }}</span>
                </div>
                <div class="conv-sub">
                    <span class="groupe-badge mini">{{ $conv['alerte']->groupeSanguin->libelle }}</span>
                    <span class="muted truncate">{{ $conv['dernier_message'] ?? 'Nouvelle conversation' }}</span>
                    @if ($conv['unread'] > 0)
                        <span class="unread-badge">{{ $conv['unread'] }}</span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="empty-state">
            <x-icon name="message" class="icon-xl"/>
            <h3>Aucune conversation</h3>
            <p class="muted">Proposez votre aide sur une alerte pour entrer en contact avec le demandeur.</p>
            <a href="{{ route('alertes.index') }}" class="btn btn-primary">Voir les alertes</a>
        </div>
    @endforelse
@endsection
