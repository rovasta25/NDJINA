@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
    <a href="{{ route('dashboard') }}" class="back-link">
        <x-icon name="arrow" class="icon-sm"/> Retour
    </a>

    <div class="page-head">
        <div>
            <h1>Mon profil</h1>
            <p class="muted">Gérez vos informations et votre mot de passe.</p>
        </div>
    </div>

    <div class="profile-grid">
        <div class="form-card">
            <h2>Informations</h2>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Téléphone (9 chiffres)</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required pattern="[0-9]{9}" maxlength="9">
                    </div>
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}" required>
                    </div>
                </div>

                @if ($user->isStructure())
                    <div class="form-group">
                        <label for="structure_type">Type de structure</label>
                        <select id="structure_type" name="structure_type">
                            @foreach (['banque_sang' => 'Banque de sang', 'hopital' => 'Hôpital', 'clinique' => 'Clinique', 'laboratoire' => 'Laboratoire', 'autre' => 'Autre'] as $value => $label)
                                <option value="{{ $value }}" {{ old('structure_type', $user->structure_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><x-icon name="check" class="icon-sm"/> Enregistrer</button>
                </div>
            </form>
        </div>

        <div class="form-card">
            <h2>Mot de passe</h2>

            <form method="POST" action="{{ route('profile.password') }}" data-confirm="Changer votre mot de passe ?">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">Mot de passe actuel</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe (8 caractères min.)</label>
                    <input type="password" id="new_password" name="new_password" required minlength="8">
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirmer le nouveau mot de passe</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-outline"><x-icon name="lock" class="icon-sm"/> Changer le mot de passe</button>
                </div>
            </form>
        </div>
    </div>
@endsection
