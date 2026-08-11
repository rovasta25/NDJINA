@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
    <div class="auth-page reveal">
        <div class="auth-brand">
            <div class="auth-brand-inner">
                <h2>Rejoignez la communauté.</h2>
                <p>Créez votre compte en une minute et devenez acteur du don de sang au Gabon.</p>
                <div class="auth-brand-points">
                    <span><x-icon name="checkc" class="icon-sm"/> Demandez et proposez</span>
                    <span><x-icon name="checkc" class="icon-sm"/> Particuliers et structures</span>
                    <span><x-icon name="checkc" class="icon-sm"/> Géolocalisation intégrée</span>
                </div>
            </div>
        </div>

        <div class="auth-form">
            <div class="auth-card">
                <h1>Créer un compte</h1>
                <p class="auth-subtitle">Particuliers et structures sanitaires peuvent demander <strong>et</strong> donner. L'administrateur est créé par le système.</p>

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="register-form">
                    @csrf

            <fieldset class="form-section">
                <legend>Type de compte</legend>

                <div class="radio-group">
                    <label class="radio-line">
                        <input type="radio" name="type" value="particulier" id="type-particulier" {{ old('type', 'particulier') === 'particulier' ? 'checked' : '' }}>
                        <span class="radio-text">
                            <strong>Particulier</strong>
                            <small>Donneur ou demandeur, à titre personnel</small>
                        </span>
                    </label>
                    <label class="radio-line">
                        <input type="radio" name="type" value="structure" id="type-structure" {{ old('type') === 'structure' ? 'checked' : '' }}>
                        <span class="radio-text">
                            <strong>Structure sanitaire</strong>
                            <small>Banque de sang, hôpital, clinique, laboratoire…</small>
                        </span>
                    </label>
                </div>
            </fieldset>

            <div id="structure-field" class="form-group" style="display: {{ old('type') === 'structure' ? 'block' : 'none' }};">
                <label for="structure_type">Type de structure</label>
                <select id="structure_type" name="structure_type">
                    <option value="">— Choisir —</option>
                    @foreach (['banque_sang' => 'Banque de sang', 'hopital' => 'Hôpital', 'clinique' => 'Clinique', 'laboratoire' => 'Laboratoire', 'autre' => 'Autre'] as $value => $label)
                        <option value="{{ $value }}" {{ old('structure_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="name">Nom complet</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Mot de passe (8 caractères min.)</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirmation</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
                </div>
            </div>

            <div class="form-group">
                <label for="phone">Téléphone (9 chiffres, ex. 074000000)</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required pattern="[0-9]{9}" maxlength="9" placeholder="074000000">
            </div>

            <div class="form-group">
                <label for="city">Ville</label>
                <input type="text" id="city" name="city" value="{{ old('city') }}" required placeholder="Libreville">
                <span class="tiny-muted" id="city-help">Saisie automatiquement lors de la localisation.</span>
            </div>

            <div class="form-group">
                <label>Localisation <span class="muted">(permet de vous trouver à proximité)</span></label>
                <div class="gps-row">
                    <button type="button" id="locate-btn" class="btn btn-outline">Me localiser</button>
                    <span id="locate-status" class="muted">Non localisé</span>
                </div>
                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Créer mon compte</button>
        </form>

        <p class="auth-alt">Déjà inscrit ? <a href="{{ route('login') }}">Connectez-vous</a></p>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    (function () {
        var typeParticulier = document.getElementById('type-particulier');
        var typeStructure = document.getElementById('type-structure');
        var structureField = document.getElementById('structure-field');

        function toggleStructure() {
            if (typeStructure.checked) {
                structureField.style.display = 'block';
            } else {
                structureField.style.display = 'none';
            }
        }

        typeParticulier.addEventListener('change', toggleStructure);
        typeStructure.addEventListener('change', toggleStructure);

        var locateBtn = document.getElementById('locate-btn');
        var locateStatus = document.getElementById('locate-status');

        locateBtn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                locateStatus.textContent = 'Géolocalisation non supportée par ce navigateur.';
                return;
            }
            locateBtn.disabled = true;
            locateStatus.textContent = 'Localisation en cours…';
            navigator.geolocation.getCurrentPosition(function (pos) {
                var lat = pos.coords.latitude.toFixed(7);
                var lng = pos.coords.longitude.toFixed(7);
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                locateStatus.textContent = 'Position obtenue, détection de la ville…';

                var city = document.getElementById('city');
                var help = document.getElementById('city-help');
                fetch('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng + '&accept-language=fr')
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        var addr = data.address || {};
                        var nom = addr.city || addr.town || addr.village || addr.municipality || addr.county || addr.state || '';
                        if (nom) {
                            city.value = nom;
                            if (help) help.textContent = 'Ville détectée automatiquement.';
                            locateStatus.textContent = 'Ville détectée : ' + nom;
                        } else {
                            locateStatus.textContent = 'Position obtenue, indiquez votre ville.';
                        }
                    })
                    .catch(function () {
                        locateStatus.textContent = 'Position obtenue, indiquez votre ville.';
                    });
            }, function () {
                locateStatus.textContent = 'Impossible d\'obtenir la position (autorisez l\'accès).';
                locateBtn.disabled = false;
            }, { enableHighAccuracy: true, timeout: 10000 });
        });
    })();
</script>
@endsection
