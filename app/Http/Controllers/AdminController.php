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

    public function setType(Request $request, User $user)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['particulier', 'structure', 'admin'])],
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $user->update(['type' => $data['type']]);

        return back()->with('success', "Rôle de {$user->name} mis à jour.");
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
