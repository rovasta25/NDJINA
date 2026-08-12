@extends('layouts.app')

@section('title', 'Alerte ' . $alerte->groupeSanguin->libelle)

@section('content')
    @php
        $estProprietaire = auth()->id() === $alerte->user_id;
        $estAdmin = auth()->user()->isAdmin();
        $maReponse = $alerte->reponses->firstWhere('user_id', auth()->id());
        $from = request('from');
        $retour = match ($from) {
            'mes' => route('alertes.mes'),
            'admin' => route('admin.alertes'),
            'message' => route('messages.index'),
            'home' => route('home'),
            'dashboard' => route('dashboard'),
            default => $estProprietaire ? route('alertes.mes') : route('alertes.index'),
        };
    @endphp

    <a href="{{ $retour }}" class="back-link">
        <x-icon name="arrow" class="icon-sm"/> Retour
    </a>

    <div class="alert-detail">
        <div class="detail-head">
            <div class="detail-badges">
                <span class="groupe-badge">{{ $alerte->groupeSanguin->libelle }}</span>
                <span class="urgence-badge urgence-{{ $alerte->urgence }}">
                    @if ($alerte->urgence === 'haute') <x-icon name="triangle" class="icon-sm"/> @endif
                    {{ $alerte->urgenceLabel() }}
                </span>
                @if ($alerte->statut !== 'active')
                    <span class="statut-badge">{{ $alerte->statut === 'terminee' ? 'Terminée' : 'Rejetée' }}</span>
                @endif
            </div>
            <div class="muted detail-time"><x-icon name="clock" class="icon-sm"/> Publiée {{ $alerte->created_at->diffForHumans() }}</div>
        </div>

        <div class="detail-body">
            <div class="detail-main">
                <h1>
                    Besoin de {{ $alerte->quantite }} poche{{ $alerte->quantite > 1 ? 's' : '' }}
                    de sang {{ $alerte->groupeSanguin->libelle }}
                </h1>

                <div class="detail-meta">
                    <div class="meta-item">
                        <x-icon name="pin" class="icon-md"/>
                        <div><strong>{{ $alerte->ville }}</strong>@if ($alerte->lieu)<br><span class="muted">{{ $alerte->lieu }}</span>@endif</div>
                    </div>
                    <div class="meta-item">
                        <x-icon name="droplet" class="icon-md"/>
                        <div><strong>{{ $alerte->quantite }} poche{{ $alerte->quantite > 1 ? 's' : '' }}</strong></div>
                    </div>
                </div>

                @if ($alerte->description)
                    <div class="detail-description">
                        <h3>Détails</h3>
                        <p>{{ $alerte->description }}</p>
                    </div>
                @endif

                @if ($alerte->statut === 'active')
                    @if ($estProprietaire)
                        <div class="action-panel panel-owner">
                            <h3>Propositions de donneurs ({{ $alerte->reponses->count() }})</h3>
                            @forelse ($alerte->reponses as $reponse)
                                <div class="response-item">
                                    <div class="response-avatar">{{ strtoupper(mb_substr($reponse->user->name, 0, 1)) }}</div>
                                    <div class="response-info">
                                        <strong>{{ $reponse->user->name }}</strong>
                                        <div class="muted">{{ $reponse->user->maskedPhone() }} · {{ $reponse->created_at->diffForHumans() }}</div>
                                        @if ($reponse->message)<p class="response-message">{{ $reponse->message }}</p>@endif
                                    </div>
                                    <div class="response-actions">
                                        <span class="statut-badge statut-{{ $reponse->statut }}">{{ $reponse->statut }}</span>
                                        @if ($reponse->statut === 'en_attente')
                                            <form method="POST" action="{{ route('reponses.confirmer', $reponse) }}" data-confirm="Confirmer {{ $reponse->user->name }} comme donneur ? Les autres propositions seront annulées.">
                                                @csrf
                                                <button class="btn btn-sm btn-primary" title="Confirmer ce donneur"><x-icon name="check" class="icon-sm"/></button>
                                            </form>
                                            <form method="POST" action="{{ route('reponses.refuser', $reponse) }}" data-confirm="Refuser la proposition de {{ $reponse->user->name }} ?">
                                                @csrf
                                                <button class="btn btn-sm btn-ghost" title="Refuser"><x-icon name="x" class="icon-sm"/></button>
                                            </form>
                                        @endif
                                        <a href="{{ route('messages.show', [$alerte, $reponse->user]) }}" class="btn btn-sm btn-outline"><x-icon name="message" class="icon-sm"/> Message</a>
                                    </div>
                                </div>
                            @empty
                                <p class="muted">Aucun donneur n'a encore proposé son aide. Soyez patient, l'alerte est diffusée en direct.</p>
                            @endforelse
                        </div>
                    @elseif ($maReponse)
                        <div class="action-panel panel-responded">
                            <h3>Vous avez proposé votre aide</h3>
                            <p>Votre proposition est <strong>{{ $maReponse->statut === 'confirmee' ? 'confirmée' : ($maReponse->statut === 'refusee' ? 'refusée' : 'en attente de confirmation') }}</strong>.</p>
                            <a href="{{ route('messages.show', [$alerte, $alerte->user]) }}" class="btn btn-primary">
                                <x-icon name="message" class="icon-sm"/> Envoyer un message
                            </a>
                        </div>
                    @else
                        <div class="action-panel panel-respond">
                            <h3>Vous pouvez aider ?</h3>
                            <p>Proposez votre aide au demandeur. Si vous êtes confirmé, vous échangerez directement.</p>
                            <form method="POST" action="{{ route('reponses.store', $alerte) }}" class="respond-form">
                                @csrf
                                <textarea name="message" rows="3" placeholder="Disponibilité, lieu de don, remarques… (optionnel)"></textarea>
                                <button type="submit" class="btn btn-primary"><x-icon name="heart" class="icon-sm"/> Proposer mon aide</button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="alert alert-error">Cette alerte n'est plus active.</div>
                @endif
            </div>

            <aside class="detail-side">
                <div class="contact-card">
                    <h3>Demandeur</h3>
                    <p class="contact-name">{{ $alerte->user->name }}</p>
                    <p class="muted">{{ $alerte->user->isStructure() ? $alerte->user->structureTypeLabel() : 'Particulier' }} · {{ $alerte->user->city }}</p>

                    @if ($estProprietaire || $estAdmin || $maReponse)
                        <a href="tel:{{ $alerte->user->phone }}" class="btn btn-primary btn-block call-btn">
                            <x-icon name="phone" class="icon-sm"/> Appeler
                        </a>
                        <p class="phone-reveal muted"><x-icon name="eye" class="icon-sm"/> {{ $alerte->user->phone }}</p>
                    @else
                        <p class="phone-masked muted"><x-icon name="lock" class="icon-sm"/> {{ $alerte->user->maskedPhone() }} · révélé après votre proposition</p>
                    @endif

                    @if ($maReponse && !$estProprietaire)
                        <a href="{{ route('messages.show', [$alerte, $alerte->user]) }}" class="btn btn-outline btn-block">
                            <x-icon name="message" class="icon-sm"/> Envoyer un message
                        </a>
                    @endif
                </div>

                @if ($estProprietaire || $estAdmin)
                    <div class="side-actions">
                        @if ($alerte->statut === 'active')
                            <form method="POST" action="{{ route('alertes.statut', $alerte) }}" data-confirm="Clôturer cette alerte ? Le besoin est-il satisfait ?">
                                @csrf
                                <input type="hidden" name="statut" value="terminee">
                                <input type="hidden" name="from" value="{{ $from }}">
                                <button type="submit" class="btn btn-outline btn-block"><x-icon name="check" class="icon-sm"/> Clôturer l'alerte</button>
                            </form>
                        @elseif ($alerte->statut === 'terminee' && $estProprietaire)
                            <form method="POST" action="{{ route('alertes.statut', $alerte) }}" data-confirm="Réactiver cette alerte ? Elle redeviendra visible par les donneurs.">
                                @csrf
                                <input type="hidden" name="statut" value="active">
                                <input type="hidden" name="from" value="{{ $from }}">
                                <button type="submit" class="btn btn-outline btn-block"><x-icon name="refresh" class="icon-sm"/> Réactiver l'alerte</button>
                            </form>
                        @endif
                        <a href="{{ route('alertes.edit', ['alerte' => $alerte, 'from' => $from]) }}" class="btn btn-outline btn-block"><x-icon name="edit" class="icon-sm"/> Modifier</a>
                        <form method="POST" action="{{ route('alertes.destroy', $alerte) }}" data-confirm="Supprimer définitivement cette alerte ?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block"><x-icon name="trash" class="icon-sm"/> Supprimer</button>
                        </form>
                    </div>
                @endif
            </aside>
        </div>
    </div>
@endsection
