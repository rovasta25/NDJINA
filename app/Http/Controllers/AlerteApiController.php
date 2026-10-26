<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use Illuminate\Http\Request;

class AlerteApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Alerte::actives()
            ->with(['user', 'groupeSanguin'])
            ->withCount('reponses')
            ->latest();

        if ($request->filled('groupe')) {
            $query->where('groupe_sanguin_id', (int) $request->input('groupe'));
        }

        if ($request->filled('urgence')) {
            $query->where('urgence', $request->input('urgence'));
        }

        if ($request->filled('ville')) {
            $query->where('ville', $request->input('ville'));
        }

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('lieu', 'like', "%{$q}%")
                    ->orWhere('ville', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $periode = $request->input('periode');
        if ($periode === 'aujourdhui') {
            $query->whereDate('created_at', today());
        } elseif ($periode === 'hier') {
            $query->whereDate('created_at', today()->subDay());
        } elseif ($periode === 'semaine') {
            $query->whereDate('created_at', '>=', today()->subWeek());
        } elseif ($periode === 'mois') {
            $query->whereDate('created_at', '>=', today()->subMonth());
        }

        $user = auth()->user();
        $userId = $user?->id;

        $originLat = is_numeric($request->input('lat'))
            ? (float) $request->input('lat')
            : ($user?->latitude !== null ? (float) $user->latitude : null);
        $originLng = is_numeric($request->input('lng'))
            ? (float) $request->input('lng')
            : ($user?->longitude !== null ? (float) $user->longitude : null);

        $alertes = $query->limit(60)->get();

        if ($originLat !== null && $originLng !== null) {
            $alertes = $alertes
                ->each(function (Alerte $alerte) use ($originLat, $originLng) {
                    $alerte->distance_km = Alerte::distanceKm(
                        $originLat,
                        $originLng,
                        $alerte->latitude,
                        $alerte->longitude
                    );
                })
                ->sortBy(fn (Alerte $alerte) => $alerte->distance_km ?? PHP_FLOAT_MAX)
                ->values();
        }

        return response()->json([
            'data' => $alertes->map(function (Alerte $alerte) use ($userId) {
                return [
                    'id' => $alerte->id,
                    'mine' => $alerte->user_id === $userId,
                    'groupe' => $alerte->groupeSanguin->libelle,
                    'quantite' => $alerte->quantite,
                    'urgence' => $alerte->urgence,
                    'urgence_label' => $alerte->urgenceLabel(),
                    'lieu' => $alerte->lieu,
                    'ville' => $alerte->ville,
                    'distance' => ($alerte->distance_km ?? null) !== null
                        ? Alerte::formatDistance($alerte->distance_km)
                        : null,
                    'description' => $alerte->description,
                    'temps' => $alerte->created_at->diffForHumans(),
                    'date' => $alerte->created_at->format('d/m/Y H:i'),
                    'reponses_count' => $alerte->reponses_count,
                    'user' => [
                        'name' => $alerte->user->name,
                        'phone' => $alerte->user->maskedPhone(),
                        'type' => $alerte->user->type,
                    ],
                ];
            }),
        ]);
    }
}
