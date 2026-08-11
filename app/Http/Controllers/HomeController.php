<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\User;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'alertes_actives' => Alerte::actives()->count(),
            'donneurs' => User::whereIn('type', ['particulier', 'structure'])->count(),
            'structures' => User::where('type', 'structure')->count(),
            'dernieres' => Alerte::actives()->with('groupeSanguin', 'user')->latest()->take(3)->get(),
        ]);
    }
}
