<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\Reponse;
use App\Services\PushNotifier;
use Illuminate\Http\Request;

class ReponseController extends Controller
{
    public function store(Request $request, Alerte $alerte)
    {
        abort_unless($alerte->statut === 'active', 403, 'Cette alerte n\'est plus active.');

        if ($alerte->user_id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas répondre à votre propre alerte.');
        }

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
        ], [
            'message.max' => 'Le message est trop long (1000 caractères max).',
        ]);

        $existant = Reponse::where('alerte_id', $alerte->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existant) {
            return back()->with('error', 'Vous avez déjà proposé votre aide pour cette alerte.');
        }

        $reponse = Reponse::create([
            'alerte_id' => $alerte->id,
            'user_id' => auth()->id(),
            'message' => $data['message'] ?? null,
        ]);

        app(PushNotifier::class)->reponse($reponse);

        return back()->with('success', 'Merci ! Votre proposition a été envoyée au demandeur. Vous pouvez échanger dans la messagerie.');
    }

    public function confirmer(Reponse $reponse)
    {
        $this->authorizeReponse($reponse);

        if ($reponse->statut !== 'en_attente') {
            return back()->with('error', 'Cette réponse a déjà été traitée.');
        }

        $reponse->update(['statut' => 'confirmee']);

        app(PushNotifier::class)->reponseConfirmee($reponse);

        Reponse::where('alerte_id', $reponse->alerte_id)
            ->where('id', '!=', $reponse->id)
            ->where('statut', 'en_attente')
            ->update(['statut' => 'refusee']);

        return back()->with('success', 'Donneur confirmé. Coordonnées échangées dans la messagerie. Les autres propositions ont été annulées.');
    }

    public function refuser(Reponse $reponse)
    {
        $this->authorizeReponse($reponse);

        if ($reponse->statut !== 'en_attente') {
            return back()->with('error', 'Cette réponse a déjà été traitée.');
        }

        $reponse->update(['statut' => 'refusee']);

        return back()->with('success', 'Réponse rejetée.');
    }

    private function authorizeReponse(Reponse $reponse): void
    {
        $alerte = $reponse->alerte;

        abort_unless(
            $alerte->user_id === auth()->id() || auth()->user()->isAdmin(),
            403,
            'Vous n\'êtes pas autorisé à gérer cette réponse.'
        );
    }
}
