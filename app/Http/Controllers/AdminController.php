<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\Reponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'total_users' => User::count(),
            'particuliers' => User::where('type', 'particulier')->count(),
            'structures' => User::where('type', 'structure')->count(),
            'total_alertes' => Alerte::count(),
            'alertes_actives' => Alerte::actives()->count(),
            'reponses' => Reponse::count(),
            'dernieres_alertes' => Alerte::with('user', 'groupeSanguin')->latest()->take(6)->get(),
        ]);
    }

    public function alertes(Request $request)
    {
        $query = Alerte::with('user', 'groupeSanguin')->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        return view('admin.alertes', ['alertes' => $query->paginate(15)->withQueryString()]);
    }

    public function setStatut(Request $request, Alerte $alerte)
    {
        $data = $request->validate([
            'statut' => ['required', Rule::in(['active', 'terminee', 'rejetee'])],
        ]);

        $alerte->update($data);

        return back()->with('success', "Statut de l'alerte modifié : {$alerte->statut}.");
    }

    public function comptes(Request $request)
    {
        $query = User::where('id', '!=', auth()->id())->withCount('alertes', 'reponses');

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        return view('admin.comptes', ['users' => $query->latest()->paginate(15)->withQueryString()]);
    }

    public function createCompte()
    {
        return view('admin.compte-create');
    }

    public function storeCompte(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', Rule::in(['admin', 'structure', 'particulier'])],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $data['city'] = $data['city'] ?? 'Libreville';

        User::create($data);

        return redirect()->route('admin.comptes')->with('success', 'Compte créé avec succès.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($user->isAdmin()) {
            $admins = User::where('type', 'admin')->count();
            if ($admins <= 1) {
                return back()->with('error', 'Impossible de supprimer le dernier administrateur.');
            }
        }

        $user->delete();

        return back()->with('success', 'Compte supprimé.');
    }
}
