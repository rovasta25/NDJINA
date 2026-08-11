<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $me = auth()->id();

        $conversations = collect();

        $mesAlertes = Alerte::where('user_id', $me)->with(['reponses.user', 'groupeSanguin'])->get();
        foreach ($mesAlertes as $alerte) {
            foreach ($alerte->reponses as $reponse) {
                if ($reponse->user_id === $me) {
                    continue;
                }
                $conversations->push($this->buildConversation($alerte, $reponse->user, $me));
            }
        }

        $mesReponses = \App\Models\Reponse::where('user_id', $me)
            ->with(['alerte.user', 'alerte.groupeSanguin'])
            ->get();
        foreach ($mesReponses as $reponse) {
            $conversations->push($this->buildConversation($reponse->alerte, $reponse->alerte->user, $me));
        }

        $conversations = $conversations->unique('key')->values();

        return view('messages.index', ['conversations' => $conversations]);
    }

    public function show(Alerte $alerte, User $user)
    {
        $me = auth()->id();
        $this->authorizeConversation($alerte, $user);

        Message::where('alerte_id', $alerte->id)
            ->where('expediteur_id', $user->id)
            ->where('destinataire_id', $me)
            ->where('lu', false)
            ->update(['lu' => true]);

        $messages = Message::where('alerte_id', $alerte->id)
            ->where(function ($q) use ($me, $user) {
                $q->where('expediteur_id', $me)->where('destinataire_id', $user->id)
                    ->orWhere('expediteur_id', $user->id)->where('destinataire_id', $me);
            })
            ->with('expediteur')
            ->orderBy('created_at')
            ->get();

        return view('messages.show', [
            'alerte' => $alerte,
            'autre' => $user,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request, Alerte $alerte, User $user)
    {
        $this->authorizeConversation($alerte, $user);

        $data = $request->validate([
            'contenu' => ['required', 'string', 'max:2000'],
        ], [
            'contenu.required' => 'Le message ne peut pas être vide.',
            'contenu.max' => 'Le message est trop long (2000 caractères max).',
        ]);

        Message::create([
            'alerte_id' => $alerte->id,
            'expediteur_id' => auth()->id(),
            'destinataire_id' => $user->id,
            'contenu' => $data['contenu'],
        ]);

        return redirect()->route('messages.show', [$alerte, $user])
            ->with('success', 'Message envoyé.');
    }

    private function authorizeConversation(Alerte $alerte, User $user): void
    {
        $me = auth()->id();
        $isOwner = $alerte->user_id === $me;
        $isOther = $alerte->user_id === $user->id;

        $isResponder = $alerte->reponses()->where('user_id', $me)->exists();
        $isOtherResponder = $alerte->reponses()->where('user_id', $user->id)->exists();

        abort_unless(
            ($isOwner && $isOtherResponder) || ($isResponder && $isOther) || auth()->user()->isAdmin(),
            403,
            'Accès à cette conversation refusé.'
        );
    }

    private function buildConversation(Alerte $alerte, User $other, int $me): array
    {
        $unread = Message::where('alerte_id', $alerte->id)
            ->where('destinataire_id', $me)
            ->where('expediteur_id', $other->id)
            ->where('lu', false)
            ->count();

        $last = Message::where('alerte_id', $alerte->id)
            ->where(function ($q) use ($me, $other) {
                $q->where('expediteur_id', $me)->where('destinataire_id', $other->id)
                    ->orWhere('expediteur_id', $other->id)->where('destinataire_id', $me);
            })
            ->latest()
            ->first();

        return [
            'key' => $alerte->id . '-' . $other->id,
            'alerte' => $alerte,
            'autre' => $other,
            'unread' => $unread,
            'dernier_message' => $last?->contenu,
            'dernier_heure' => $last?->created_at?->diffForHumans(),
        ];
    }
}
