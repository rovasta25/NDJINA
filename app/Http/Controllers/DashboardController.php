<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\Message;
use App\Models\Reponse;
use App\Models\User;

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
                    'total_users' => User::count(),
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
            ->latest()
            ->take(60)
            ->get();

        if ($user->latitude !== null && $user->longitude !== null) {
            $recentes = $recentes
                ->each(function (Alerte $alerte) use ($user) {
                    $alerte->distance_km = Alerte::distanceKm(
                        (float) $user->latitude,
                        (float) $user->longitude,
                        $alerte->latitude,
                        $alerte->longitude
                    );
                })
                ->sortBy(fn (Alerte $alerte) => $alerte->distance_km ?? PHP_FLOAT_MAX)
                ->take(5)
                ->values();
        } else {
            $recentes = $recentes->take(5);
        }

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentes' => $recentes,
            'mes_alertes_recentes' => $mesAlertes->with('groupeSanguin', 'reponses')->latest()->take(4)->get(),
        ]);
    }
}
