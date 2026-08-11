<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlerteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'groupe_sanguin_id' => ['required', 'exists:groupe_sanguins,id'],
            'quantite' => ['required', 'integer', 'min:1', 'max:100'],
            'urgence' => ['required', Rule::in(['haute', 'moyenne', 'basse'])],
            'lieu' => ['nullable', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'groupe_sanguin_id.required' => 'Le groupe sanguin est obligatoire.',
            'groupe_sanguin_id.exists' => 'Le groupe sanguin choisi n\'existe pas.',
            'quantite.required' => 'Le nombre de poches est obligatoire.',
            'quantite.integer' => 'Le nombre de poches doit être un nombre.',
            'quantite.min' => 'La quantité doit être d\'au moins 1 poche.',
            'quantite.max' => 'La quantité ne peut pas dépasser 100 poches.',
            'urgence.required' => 'Le niveau d\'urgence est obligatoire.',
            'urgence.in' => 'Le niveau d\'urgence choisi n\'est pas valide.',
            'ville.required' => 'La ville est obligatoire.',
            'ville.max' => 'La ville est trop longue.',
            'description.max' => 'La description est trop longue (1000 caractères max).',
        ];
    }
}
