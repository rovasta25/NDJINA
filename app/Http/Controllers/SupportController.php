<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    private function admin(): User
    {
        return User::where('type', 'admin')->orderBy('id')->firstOrFail();
    }

    private function thread(User $me, User $admin, bool $markRead = false)
    {
        $query = Message::whereNull('alerte_id')
            ->where(function ($q) use ($me, $admin) {
                $q->where('expediteur_id', $me->id)->where('destinataire_id', $admin->id)
                    ->orWhere('expediteur_id', $admin->id)->where('destinataire_id', $me->id);
            })
            ->orderBy('created_at');

        if ($markRead) {
            Message::whereNull('alerte_id')
                ->where('destinataire_id', $me->id)
                ->where('expediteur_id', $admin->id)
                ->where('lu', false)
                ->update(['lu' => true]);
        }

        return $query->with('expediteur')->get();
    }

    public function userIndex()
    {
        $me = auth()->user();
        $admin = $this->admin();

        return view('support.index', [
            'admin' => $admin,
            'messages' => $this->thread($me, $admin),
        ]);
    }

    public function userStore(Request $request)
    {
        $admin = $this->admin();

        $data = $request->validate([
            'objet' => ['required', 'string', 'max:120'],
            'contenu' => ['required', 'string', 'max:2000'],
        ], [
            'objet.required' => "Précisez l'objet de votre demande.",
            'objet.max' => "L'objet est trop long (120 caractères max).",
            'contenu.required' => 'Le message ne peut pas être vide.',
            'contenu.max' => 'Le message est trop long (2000 caractères max).',
        ]);

        Message::create([
            'alerte_id' => null,
            'expediteur_id' => auth()->id(),
            'destinataire_id' => $admin->id,
            'objet' => $data['objet'],
            'contenu' => $data['contenu'],
        ]);

        return redirect()->route('support.index')
            ->with('success', 'Votre message a été envoyé à l\'administrateur.');
    }

    public function adminIndex()
    {
        $me = auth()->id();

        $users = User::where('type', '!=', 'admin')
            ->whereExists(function ($q) {
                $q->selectRaw(1)->from('messages')
                    ->whereNull('messages.alerte_id')
                    ->where(function ($w) {
                        $w->whereColumn('messages.expediteur_id', 'users.id')
                            ->orWhereColumn('messages.destinataire_id', 'users.id');
                    });
            })
            ->get();

        $conversations = $users->map(function (User $user) use ($me) {
            $unread = Message::whereNull('alerte_id')
                ->where('destinataire_id', $me)
                ->where('expediteur_id', $user->id)
                ->where('lu', false)
                ->count();

            $last = Message::whereNull('alerte_id')
                ->where(function ($q) use ($me, $user) {
                    $q->where('expediteur_id', $me)->where('destinataire_id', $user->id)
                        ->orWhere('expediteur_id', $user->id)->where('destinataire_id', $me);
                })
                ->latest()
                ->first();

            return [
                'user' => $user,
                'unread' => $unread,
                'objet' => $last?->objet,
                'dernier_message' => $last?->contenu,
                'dernier_heure' => $last?->created_at?->diffForHumans(),
                'traite' => $last?->traite ?? false,
            ];
        })->sortByDesc('dernier_heure');

        return view('admin.support', ['conversations' => $conversations]);
    }

    public function adminShow(User $user)
    {
        if ($user->isAdmin()) {
            abort(403);
        }

        return view('admin.support-show', [
            'autre' => $user,
            'messages' => $this->thread($user, auth()->user(), true),
        ]);
    }

    public function adminStore(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'contenu' => ['required', 'string', 'max:2000'],
        ], [
            'contenu.required' => 'Le message ne peut pas être vide.',
            'contenu.max' => 'Le message est trop long (2000 caractères max).',
        ]);

        Message::create([
            'alerte_id' => null,
            'expediteur_id' => auth()->id(),
            'destinataire_id' => $user->id,
            'objet' => Message::whereNull('alerte_id')
                ->where(function ($q) use ($user) {
                    $q->where('expediteur_id', $user->id)
                        ->orWhere('destinataire_id', $user->id);
                })
                ->value('objet'),
            'contenu' => $data['contenu'],
        ]);

        return redirect()->route('admin.support.show', $user)
            ->with('success', 'Réponse envoyée à ' . $user->name . '.');
    }

    public function adminTraite(User $user)
    {
        if ($user->isAdmin()) {
            abort(403);
        }

        $me = auth()->id();

        $messages = Message::whereNull('alerte_id')
            ->where(function ($q) use ($me, $user) {
                $q->where('expediteur_id', $me)->where('destinataire_id', $user->id)
                    ->orWhere('expediteur_id', $user->id)->where('destinataire_id', $me);
            });

        $dernier = $messages->latest()->first();

        if ($dernier) {
            $dernier->update(['traite' => !$dernier->traite]);
        }

        return back()->with('success', $dernier && $dernier->traite
            ? 'Demande marquée comme traitée.'
            : 'Demande rouverte.');
    }
}
