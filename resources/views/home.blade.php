@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <section class="hero">
        <div class="hero-content reveal">
            <span class="hero-eyebrow">Plateforme gabonaise de don de sang</span>
            <div class="hero-brandname">NDJINA</div>
            <h1>Quand chaque minute compte,<br><span class="hero-accent">chaque donneur compte.</span></h1>
            <p>NDJINA est la plateforme qui met en relation, en temps réel, les personnes ayant besoin de sang et les donneurs volontaires compatibles — par groupe sanguin, urgence et localisation.</p>
            <div class="hero-actions">
                @auth
                    <a href="{{ route('alertes.index') }}" class="btn btn-primary btn-lg"><x-icon name="bell" class="icon-sm"/> Voir les alertes</a>
                    <a href="{{ route('alertes.create') }}" class="btn btn-outline btn-lg"><x-icon name="plus" class="icon-sm"/> Publier une alerte</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg"><x-icon name="heart" class="icon-sm"/> Devenir donneur</a>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-lg">Se connecter</a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Le problème --}}
    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow eyebrow-red">Le constat</span>
                <h2>Chaque minute compte. Aujourd'hui, elles se perdent.</h2>
                <p class="lead">Trouver un donneur de sang en urgence est encore trop souvent une course d'obstacles.</p>
            </div>
            <div class="grid-3">
                <div class="soft-card">
                    <span class="soft-icon stat-red"><x-icon name="phone" class="icon-lg"/></span>
                    <h3>Des appels en cascade</h3>
                    <p>La famille et les proches appellent de proche en proche, sans savoir qui est compatible ni où se trouve un donneur disponible.</p>
                </div>
                <div class="soft-card">
                    <span class="soft-icon stat-amber"><x-icon name="message" class="icon-lg"/></span>
                    <h3>Des réseaux sociaux non fiables</h3>
                    <p>Les demandes diffusées sur les réseaux ne donnent ni le groupe sanguin précis, ni l'urgence réelle, ni un suivi de la réponse.</p>
                </div>
                <div class="soft-card">
                    <span class="soft-icon stat-blue"><x-icon name="clock" class="icon-lg"/></span>
                    <h3>Un temps précieux perdu</h3>
                    <p>Chaque heure perdue dans la recherche retarde la prise en charge, et met en danger la vie du patient qui attend.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Le but / mission --}}
    <section class="section section-dark">
        <div class="container">
            <div class="mission reveal left">
                <span class="eyebrow eyebrow-light">Notre mission</span>
                <h2>Réduire le délai entre le besoin et le donneur.</h2>
                <p>NDJINA existe pour un seul but : faire gagner du temps aux malades et aux centres de santé, en rendant chaque demande de sang visible, précise et traçable. Une personne a besoin de sang ? Une alerte est publiée. Les donneurs compatibles sont informés immédiatement. La mise en relation se fait en quelques minutes.</p>
                <div class="mission-badges">
                    <span class="mission-badge"><x-icon name="checkc" class="icon-sm"/> Mise en relation rapide</span>
                    <span class="mission-badge"><x-icon name="checkc" class="icon-sm"/> Groupe sanguin et lieu précis</span>
                    <span class="mission-badge"><x-icon name="checkc" class="icon-sm"/> Historique traçable</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Le besoin --}}
    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow eyebrow-red">Pourquoi NDJINA</span>
                <h2>Un besoin vital, une solution simple</h2>
                <p class="lead">Le don de sang ne peut pas être fabriqué. Il repose uniquement sur la générosité des donneurs.</p>
            </div>
            <div class="facts-grid">
                <div class="fact-card">
                    <strong class="fact-num">3</strong>
                    <span class="fact-label">vies peuvent être sauvées avec une seule poche de sang.</span>
                </div>
                <div class="fact-card">
                    <strong class="fact-num">8</strong>
                    <span class="fact-label">groupes sanguins sont suivis sur la plateforme (A, B, AB, O, rhésus + et −).</span>
                </div>
                <div class="fact-card">
                    <strong class="fact-num">24h/24</strong>
                    <span class="fact-label">les alertes restent visibles et actualisées en temps réel.</span>
                </div>
                <div class="fact-card">
                    <strong class="fact-num">2</strong>
                    <span class="fact-label">types de comptes : particuliers et structures sanitaires, qui peuvent tous demander et donner.</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Comment ça marche --}}
    <section class="section section-alt">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow eyebrow-red">Simple et rapide</span>
                <h2>Comment ça marche ?</h2>
                <p class="lead">Un processus clair, en trois étapes.</p>
            </div>
            <div class="steps-grid">
                <div class="step-card">
                    <span class="step-num">1</span>
                    <h3>Publiez un besoin</h3>
                    <p>Indiquez le groupe sanguin, la quantité de poches, l'urgence et le lieu. Votre alerte est diffusée immédiatement.</p>
                </div>
                <div class="step-card">
                    <span class="step-num">2</span>
                    <h3>Les donneurs répondent</h3>
                    <p>Les volontaires compatibles proposent leur aide en quelques clics, avec leur numéro visible uniquement pour le demandeur.</p>
                </div>
                <div class="step-card">
                    <span class="step-num">3</span>
                    <h3>Entrez en contact</h3>
                    <p>Confirmez une proposition et échangez directement via la messagerie intégrée pour organiser le don.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Fonctionnalités --}}
    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow eyebrow-red">La plateforme</span>
                <h2>Des fonctionnalités pensées pour l'urgence</h2>
                <p class="lead">Chaque détail est conçu pour aller plus vite et protéger les utilisateurs.</p>
            </div>
            <div class="grid-3">
                <div class="feature-card">
                    <span class="feature-icon"><x-icon name="bell" class="icon-lg"/></span>
                    <h3>Alertes en temps réel</h3>
                    <p>Chaque demande est actualisée en direct sur la plateforme, sans rechargement de page.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon stat-blue"><x-icon name="filter" class="icon-lg"/></span>
                    <h3>Filtres précis</h3>
                    <p>Recherchez par groupe sanguin, niveau d'urgence, période et ville pour trouver la bonne alerte.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon stat-green"><x-icon name="lock" class="icon-lg"/></span>
                    <h3>Confidentialité</h3>
                    <p>Numéros de téléphone masqués en liste, révélés uniquement sur l'alerte concernée.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon stat-amber"><x-icon name="send" class="icon-lg"/></span>
                    <h3>Messagerie intégrée</h3>
                    <p>Échangez directement avec le demandeur ou le donneur, sans quitter la plateforme.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon stat-red"><x-icon name="checkc" class="icon-lg"/></span>
                    <h3>Confirmations traçables</h3>
                    <p>Chaque proposition peut être confirmée ou refusée, avec un historique clair des réponses.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon stat-blue"><x-icon name="users" class="icon-lg"/></span>
                    <h3>Comptes adaptés</h3>
                    <p>Particuliers, banques de sang, hôpitaux et cliniques : chaque profil a son espace dédié.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Disponibilité / périmètre --}}
    <section class="section section-alt">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow eyebrow-red">Disponibilité</span>
                <h2>Où et pour qui ?</h2>
                <p class="lead">NDJINA est accessible partout, pour tout le monde.</p>
            </div>
            <div class="grid-3">
                <div class="soft-card">
                    <span class="soft-icon stat-green"><x-icon name="globe" class="icon-lg"/></span>
                    <h3>Sur le web</h3>
                    <p>Accessible depuis n'importe quel navigateur, sur ordinateur comme sur mobile. Aucune installation requise.</p>
                </div>
                <div class="soft-card">
                    <span class="soft-icon stat-red"><x-icon name="pin" class="icon-lg"/></span>
                    <h3>Au Gabon</h3>
                    <p>Conçu pour le contexte gabonais : villes, structures sanitaires locales et besoins réels du terrain.</p>
                </div>
                <div class="soft-card">
                    <span class="soft-icon stat-blue"><x-icon name="heart" class="icon-lg"/></span>
                    <h3>Pour tous</h3>
                    <p>Demandeurs, donneurs, structures sanitaires et administrateurs : chacun trouve sa place sur la plateforme.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Dernières alertes --}}
    @if ($dernieres->count())
        <section class="section">
            <div class="container">
                <div class="section-head reveal">
                    <div>
                        <span class="eyebrow eyebrow-red">En ce moment</span>
                        <h2>Dernières alertes</h2>
                    </div>
                    @auth
                        <a href="{{ route('alertes.index') }}" class="btn btn-ghost btn-sm">Tout voir <x-icon name="arrow" class="icon-sm"/></a>
                    @endauth
                </div>
                <div class="alerts-mini">
                    @foreach ($dernieres as $alerte)
                        <a href="{{ route('alertes.show', ['alerte' => $alerte, 'from' => 'home']) }}" class="mini-alert">
                            <span class="groupe-badge">{{ $alerte->groupeSanguin->libelle }}</span>
                            <span class="mini-alert-text">{{ $alerte->ville }} · {{ $alerte->quantite }} poche{{ $alerte->quantite > 1 ? 's' : '' }} — {{ $alerte->user->maskedPhone() }}</span>
                            <span class="mini-alert-time">{{ $alerte->created_at->diffForHumans() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section section-cta">
        <div class="container">
            <div class="cta-strip reveal">
                <div>
                    <h2>Prêt à faire la différence ?</h2>
                    <p>Un don peut sauver jusqu'à trois vies. Rejoignez la communauté NDJINA.</p>
                </div>
                @auth
                    <a href="{{ route('alertes.create') }}" class="btn btn-primary btn-lg"><x-icon name="plus" class="icon-sm"/> Publier une alerte</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg"><x-icon name="heart" class="icon-sm"/> Créer un compte</a>
                @endauth
            </div>
        </div>
    </section>
@endsection

