@extends('layouts.app')

@section('title', 'Contacter l\'administrateur')

@section('content')
    <a href="{{ route('dashboard') }}" class="back-link">
        <x-icon name="arrow" class="icon-sm"/> Retour au tableau de bord
    </a>

    <div class="page-head">
        <div>
            <h1>Contacter l'administrateur</h1>
            <p class="muted">Un problème, une question, un compte à signaler ? Écrivez-nous.</p>
        </div>
    </div>

    @if ($messages->isEmpty())
        <div class="form-card" style="max-width:640px">
            <h2>Envoyer un message</h2>
            <p class="muted" style="margin-bottom:18px">
                L'équipe NDJINA vous répondra directement dans cette conversation.
            </p>
            <form method="POST" action="{{ route('support.store') }}">
                @csrf
                <div class="form-group">
                    <label for="objet">Objet</label>
                    <input type="text" id="objet" name="objet" value="{{ old('objet') }}" required maxlength="120" placeholder="Ex. : compte à signaler, problème de connexion…">
                    @error('objet')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="contenu">Message</label>
                    <textarea id="contenu" name="contenu" rows="5" required maxlength="2000" placeholder="Décrivez votre problème…">{{ old('contenu') }}</textarea>
                    @error('contenu')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    @else
        <div class="chat-wrap" style="max-width:720px">
            <div class="chat-head">
                <div class="conv-avatar sm">A</div>
                <div class="chat-head-info">
                    <strong>Administration NDJINA</strong>
                    <span class="muted">Support</span>
                </div>
            </div>

            <div class="chat-body" id="chat-body">
                @foreach ($messages as $message)
                    <div class="msg {{ $message->expediteur_id === auth()->id() ? 'msg-mine' : 'msg-other' }}">
                        <div class="msg-bubble">{{ $message->contenu }}</div>
                        <div class="msg-time muted">{{ $message->created_at->format('d/m H:i') }}</div>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('support.store') }}" class="chat-form">
                @csrf
                <input type="hidden" name="objet" value="{{ $messages->first()->objet }}">
                <input type="text" name="contenu" placeholder="Répondre…" required maxlength="2000" autocomplete="off">
                <button type="submit" class="btn btn-primary"><x-icon name="send" class="icon-sm"/></button>
            </form>
        </div>
    @endif
@endsection

@section('scripts')
<script>
    (function () {
        var body = document.getElementById('chat-body');
        if (body) body.scrollTop = body.scrollHeight;
    })();
</script>
@endsection
