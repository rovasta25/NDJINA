<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\Message;
use App\Models\Reponse;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return view('dashboard', [
                'user' => $user,
                'stats' => [
                    'alertes_actives' => Alerte::actives()->count(),
                    'total_alertes' => Alerte::count(),
                    'total_users' => \App\Models\User::count(),
                ],
                'recentes' => Alerte::with('user', 'groupeSanguin')->latest()->take(5)->get(),
            ]);
        }

        $mesAlertes = $user->alertes();
        $mesReponses = $user->reponses();

        $stats = [
            'mes_alertes' => (clone $mesAlertes)->count(),
            'alertes_actives' => (clone $mesAlertes)->where('statut', 'active')->count(),
            'reponses_recues' => Reponse::whereIn('alerte_id', (clone $mesAlertes)->pluck('id'))->count(),
            'mes_reponses' => (clone $mesReponses)->count(),
            'non_lus' => Message::where('destinataire_id', $user->id)->where('lu', false)->count(),
        ];

        $recentes = Alerte::actives()
            ->with('user', 'groupeSanguin')
            ->where('ville', $user->city)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentes' => $recentes,
            'mes_alertes_recentes' => $mesAlertes->with('groupeSanguin', 'reponses')->latest()->take(4)->get(),
        ]);
    }
}
