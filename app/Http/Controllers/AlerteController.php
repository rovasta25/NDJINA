<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlerteRequest;
use App\Models\Alerte;
use App\Models\GroupeSanguin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlerteController extends Controller
{
    public function index()
    {
        $groupes = GroupeSanguin::orderBy('libelle')->get();
        $villes = Alerte::actives()->whereNotNull('ville')->distinct()->orderBy('ville')->pluck('ville');

        return view('alertes.index', [
            'groupes' => $groupes,
            'villes' => $villes,
        ]);
    }

    public function create()
    {
        $groupes = GroupeSanguin::orderBy('libelle')->get();

        return view('alertes.create', ['groupes' => $groupes]);
    }

    public function store(AlerteRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Alerte::create($data);

        return redirect()->route('alertes.mes')
            ->with('success', 'Votre alerte a été publiée. Elle est maintenant visible par les donneurs proches.');
    }

    public function show(Alerte $alerte)
    {
        if ($alerte->statut !== 'active' && !auth()->user()->isAdmin() && $alerte->user_id !== auth()->id()) {
            abort(404);
        }

        $alerte->load(['user', 'groupeSanguin', 'reponses.user']);

        return view('alertes.show', ['alerte' => $alerte]);
    }

    public function edit(Alerte $alerte)
    {
        $this->authorize('modify', $alerte);

        $groupes = GroupeSanguin::orderBy('libelle')->get();

        return view('alertes.edit', ['alerte' => $alerte, 'groupes' => $groupes]);
    }

    public function update(AlerteRequest $request, Alerte $alerte)
    {
        $this->authorize('modify', $alerte);

        $alerte->update($request->validated());

        return redirect()->route('alertes.show', [
            'alerte' => $alerte,
            'from' => $request->input('from'),
        ])->with('success', 'Votre alerte a été mise à jour.');
    }

    public function destroy(Alerte $alerte)
    {
        $this->authorize('modify', $alerte);

        $alerte->delete();

        return redirect()->route('alertes.mes')
            ->with('success', 'L\'alerte a été supprimée.');
    }

    public function setStatut(Request $request, Alerte $alerte)
    {
        $this->authorize('modify', $alerte);

        $data = $request->validate([
            'statut' => ['required', Rule::in(['active', 'terminee'])],
        ]);

        $alerte->update($data);

        $message = $alerte->statut === 'terminee'
            ? 'Votre alerte a été clôturée. Merci pour le signalement du besoin !'
            : 'Votre alerte est de nouveau active et visible par les donneurs.';

        return redirect()->route('alertes.show', [
            'alerte' => $alerte,
            'from' => $request->input('from'),
        ])->with('success', $message);
    }

    public function mesAlertes()
    {
        $alertes = auth()->user()->alertes()
            ->with('groupeSanguin', 'reponses')
            ->latest()
            ->get();

        return view('alertes.mes', ['alertes' => $alertes]);
    }
}
