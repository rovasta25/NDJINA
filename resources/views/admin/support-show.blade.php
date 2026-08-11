@extends('layouts.app')

@section('title', 'Support — ' . $autre->name)

@section('content')
    <a href="{{ route('admin.support') }}" class="back-link">
        <x-icon name="arrow" class="icon-sm"/> Retour
    </a>

    <div class="chat-wrap" style="max-width:720px">
        <div class="chat-head">
            <div class="conv-avatar sm">{{ strtoupper(mb_substr($autre->name, 0, 1)) }}</div>
            <div class="chat-head-info">
                <strong>{{ $autre->name }}</strong>
                <span class="muted">{{ $autre->isStructure() ? $autre->structureTypeLabel() : 'Particulier' }}</span>
            </div>
            <form method="POST" action="{{ route('admin.support.traite', $autre) }}" class="inline-form">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-ghost btn-sm" data-confirm="{{ $messages->last()->traite ?? false ? 'Rouvrir cette demande ?' : 'Marquer cette demande comme traitée ?' }}">
                    {{ $messages->last()->traite ?? false ? 'Rouvrir' : 'Marquer traité' }}
                </button>
            </form>
        </div>

        <div class="chat-body" id="chat-body">
            @forelse ($messages as $message)
                <div class="msg {{ $message->expediteur_id === auth()->id() ? 'msg-mine' : 'msg-other' }}">
                    <div class="msg-bubble">{{ $message->contenu }}</div>
                    <div class="msg-time muted">{{ $message->created_at->format('d/m H:i') }}</div>
                </div>
            @empty
                <div class="empty-state">
                    <x-icon name="message" class="icon-xl"/>
                    <p class="muted">Aucun échange pour le moment.</p>
                </div>
            @endforelse
        </div>

        <form method="POST" action="{{ route('admin.support.store', $autre) }}" class="chat-form">
            @csrf
            <input type="text" name="contenu" placeholder="Répondre à {{ $autre->name }}…" required maxlength="2000" autocomplete="off">
            <button type="submit" class="btn btn-primary"><x-icon name="send" class="icon-sm"/></button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    (function () {
        var body = document.getElementById('chat-body');
        if (body) body.scrollTop = body.scrollHeight;
    })();
</script>
@endsection
