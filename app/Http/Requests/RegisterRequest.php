<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', Rule::in(['particulier', 'structure'])],
            'structure_type' => ['required_if:type,structure', Rule::in(['banque_sang', 'hopital', 'clinique', 'laboratoire', 'autre'])],
            'phone' => ['required', 'regex:/^[0-9]{9}$/'],
            'city' => ['required', 'string', 'max:255'],
           'latitude' => ['nullable', 'numeric', 'between:-90,90'],
           'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom complet est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.unique' => 'Un compte existe déjà avec cette adresse e-mail.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'type.required' => 'Le type de compte est obligatoire.',
            'type.in' => 'Le type de compte choisi n\'est pas valide.',
            'structure_type.required_if' => 'Veuillez choisir le type de structure sanitaire.',
            'structure_type.in' => 'Le type de structure choisi n\'est pas valide.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.regex' => 'Le numéro de téléphone doit contenir exactement 9 chiffres.',
            'city.required' => 'La ville est obligatoire.',
            'latitude.between' => 'La latitude n\'est pas valide.',
            'longitude.between' => 'La longitude n\'est pas valide.',
        ];
    }
}
