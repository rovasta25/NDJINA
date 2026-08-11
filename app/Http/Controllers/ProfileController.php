<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9]{9}$/'],
            'city' => ['required', 'string', 'max:255'],
            'structure_type' => [
                Rule::requiredIf($user->isStructure()),
                Rule::in(['banque_sang', 'hopital', 'clinique', 'laboratoire', 'autre']),
            ],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'phone.required' => 'Le téléphone est obligatoire.',
            'phone.regex' => 'Le téléphone doit contenir exactement 9 chiffres.',
            'city.required' => 'La ville est obligatoire.',
            'structure_type.required' => 'Le type de structure est obligatoire.',
            'structure_type.in' => 'Le type de structure n\'est pas valide.',
        ]);

        $user->update($data);

        return back()->with('success', 'Profil mis à jour.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Votre mot de passe actuel est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'La confirmation ne correspond pas.',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update(['password' => $data['new_password']]);

        return back()->with('success', 'Mot de passe mis à jour.');
    }
}
