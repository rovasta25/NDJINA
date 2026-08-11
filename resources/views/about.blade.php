@extends('layouts.app')

@section('title', 'À propos')

@section('content')
    <section class="hero hero-compact">
        <div class="hero-content reveal">
            <span class="hero-eyebrow">À propos de NDJINA</span>
            <h1>Une plateforme au service de <span class="hero-accent">la vie</span>.</h1>
            <p>NDJINA est née d'un constat simple : au Gabon, trouver un donneur de sang en urgence prend encore trop de temps. Nous existons pour raccourcir ce délai.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow eyebrow-red">Notre mission</span>
                <h2>Réduire le temps entre le besoin et le donneur</h2>
                <p class="lead">Chaque minute compte dans une urgence de sang. NDJINA transforme une recherche chaotique en un processus clair, rapide et traçable.</p>
            </div>
            <div class="grid-3">
                <div class="soft-card">
                    <span class="soft-icon stat-red"><x-icon name="bell" class="icon-lg"/></span>
                    <h3>Diffusion immédiate</h3>
                    <p>Une alerte publiée est visible en temps réel par tous les utilisateurs connectés, avec groupe sanguin, quantité et lieu.</p>
                </div>
                <div class="soft-card">
                    <span class="soft-icon stat-blue"><x-icon name="pin" class="icon-lg"/></span>
                    <h3>Ciblage précis</h3>
                    <p>Filtres par groupe, urgence, période et ville : le bon donneur trouve la bonne demande, sans bruit inutile.</p>
                </div>
                <div class="soft-card">
                    <span class="soft-icon stat-green"><x-icon name="checkc" class="icon-lg"/></span>
                    <h3>Suivi et traçabilité</h3>
                    <p>Chaque proposition est confirmée ou refusée. L'historique des réponses est conservé pour un don organisé.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-dark">
        <div class="container">
            <div class="mission reveal left">
                <span class="eyebrow eyebrow-light">Pourquoi NDJINA</span>
                <h2>Parce que le sang ne se fabrique pas.</h2>
                <p>Le sang ne peut être ni produit ni stocké indéfiniment. Il repose uniquement sur la générosité des donneurs. Pourtant, la recherche d'un donneur reste souvent informelle : appels en cascade, messages perdus sur les réseaux, déplacements inutiles. NDJINA structure cette démarche essentielle pour que la générosité arrive au bon endroit, au bon moment.</p>
                <div class="mission-badges">
                    <span class="mission-badge"><x-icon name="checkc" class="icon-sm"/> Une alerte claire</span>
                    <span class="mission-badge"><x-icon name="checkc" class="icon-sm"/> Un groupe sanguin précis</span>
                    <span class="mission-badge"><x-icon name="checkc" class="icon-sm"/> Une mise en relation directe</span>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow eyebrow-red">Nos valeurs</span>
                <h2>Ce qui guide nos choix</h2>
            </div>
            <div class="grid-3">
                <div class="soft-card">
                    <span class="soft-icon stat-amber"><x-icon name="clock" class="icon-lg"/></span>
                    <h3>La rapidité</h3>
                    <p>Une interface simple et des alertes en direct, pour agir en quelques minutes.</p>
                </div>
                <div class="soft-card">
                    <span class="soft-icon stat-green"><x-icon name="lock" class="icon-lg"/></span>
                    <h3>La confidentialité</h3>
                    <p>Numéros masqués en liste, révélés uniquement sur l'alerte. Vos données restent protégées.</p>
                </div>
                <div class="soft-card">
                    <span class="soft-icon stat-red"><x-icon name="heart" class="icon-lg"/></span>
                    <h3>L'engagement</h3>
                    <p>Particuliers et structures sanitaires unis autour d'un même objectif : sauver des vies.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow eyebrow-red">Comment ça marche</span>
                <h2>Un processus en quatre étapes</h2>
            </div>
            <div class="steps-grid">
                <div class="step-card">
                    <span class="step-num">1</span>
                    <h3>Publier l'alerte</h3>
                    <p>Le demandeur indique le groupe, la quantité, l'urgence et la ville.</p>
                </div>
                <div class="step-card">
                    <span class="step-num">2</span>
                    <h3>Diffusion en direct</h3>
                    <p>L'alerte est affichée en temps réel sur la plateforme.</p>
                </div>
                <div class="step-card">
                    <span class="step-num">3</span>
                    <h3>Proposition du donneur</h3>
                    <p>Un donneur compatible propose son aide, avec son numéro révélé au demandeur.</p>
                </div>
                <div class="step-card">
                    <span class="step-num">4</span>
                    <h3>Organisation du don</h3>
                    <p>Les deux parties échangent via la messagerie et organisent la rencontre.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-cta">
        <div class="container">
            <div class="cta-strip reveal">
                <div>
                    <h2>Une question, un retour ?</h2>
                    <p>Ce projet est réalisé dans le cadre du DUT Informatique — IST. Votre retour nous aide à améliorer la plateforme.</p>
                </div>
                <div class="cta-strip-actions">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg"><x-icon name="heart" class="icon-sm"/> Rejoindre NDJINA</a>
                </div>
            </div>
        </div>
    </section>
@endsection

