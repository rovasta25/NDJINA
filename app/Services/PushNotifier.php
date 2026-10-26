<?php

namespace App\Services;

use App\Models\Alerte;
use App\Models\Message;
use App\Models\PushSubscription;
use App\Models\Reponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Throwable;

class PushNotifier
{
    public function alerte(Alerte $alerte): void
    {
        $alerte->loadMissing('groupeSanguin');

        $this->dispatch([
            'title' => 'Besoin de sang '.$alerte->groupeSanguin->libelle,
            'body' => $alerte->quantite.' poche'.($alerte->quantite > 1 ? 's' : '')
                .' — '.$alerte->ville.($alerte->lieu ? ' ('.$alerte->lieu.')' : ''),
            'url' => route('alertes.show', $alerte),
            'tag' => 'alerte-'.$alerte->id,
        ], exceptUserId: $alerte->user_id);
    }

    public function message(Message $message): void
    {
        $message->loadMissing(['expediteur', 'alerte']);

        $this->dispatch([
            'title' => 'Nouveau message de '.$message->expediteur->name,
            'body' => Str::limit($message->contenu, 140),
            'url' => route('messages.show', [$message->alerte_id, $message->expediteur_id]),
            'tag' => 'message-'.$message->alerte_id.'-'.$message->expediteur_id,
        ], onlyUserId: $message->destinataire_id, exceptUserId: $message->expediteur_id);
    }

    public function reponse(Reponse $reponse): void
    {
        $reponse->loadMissing(['user', 'alerte.groupeSanguin']);

        $this->dispatch([
            'title' => 'Nouvelle réponse à votre alerte',
            'body' => $reponse->user->name.' propose de donner du '.$reponse->alerte->groupeSanguin->libelle
                .' — '.$reponse->alerte->ville,
            'url' => route('alertes.show', $reponse->alerte_id),
            'tag' => 'reponse-'.$reponse->alerte_id,
        ], onlyUserId: $reponse->alerte->user_id, exceptUserId: $reponse->user_id);
    }

    public function reponseConfirmee(Reponse $reponse): void
    {
        $reponse->loadMissing('alerte.groupeSanguin');

        $this->dispatch([
            'title' => 'Votre proposition a été confirmée',
            'body' => 'Le demandeur retient votre don de '.$reponse->alerte->groupeSanguin->libelle
                .'. Échangez avec lui dans la messagerie.',
            'url' => route('messages.show', [$reponse->alerte_id, $reponse->alerte->user_id]),
            'tag' => 'reponse-'.$reponse->alerte_id,
        ], onlyUserId: $reponse->user_id);
    }

    /**
     * @param  array<string, string>  $payload
     */
    private function dispatch(array $payload, ?int $onlyUserId = null, ?int $exceptUserId = null): void
    {
        $publicKey = config('webpush.public_key');
        $privateKey = config('webpush.private_key');

        if (! $publicKey || ! $privateKey) {
            return;
        }

        if ($onlyUserId !== null && $onlyUserId === $exceptUserId) {
            return;
        }

        $query = PushSubscription::query();

        if ($onlyUserId !== null) {
            $query->where('user_id', $onlyUserId);
        } elseif ($exceptUserId !== null) {
            $query->where('user_id', '!=', $exceptUserId);
        }

        $subscriptions = $query->limit(300)->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        try {
            $webPush = new WebPush([
                'VAPID' => [
                    'subject' => config('webpush.subject'),
                    'publicKey' => $publicKey,
                    'privateKey' => $privateKey,
                ],
            ]);

            foreach ($subscriptions as $subscription) {
                $webPush->queueNotification(
                    new Subscription(
                        $subscription->endpoint,
                        $subscription->public_key,
                        $subscription->auth_token,
                        $subscription->content_encoding,
                    ),
                    json_encode($payload, JSON_UNESCAPED_UNICODE),
                    ['TTL' => config('webpush.ttl')]
                );
            }

            foreach ($webPush->flush() as $report) {
                if ($report->isSubscriptionExpired()) {
                    PushSubscription::where('endpoint_hash', hash('sha256', $report->getEndpoint()))->delete();
                } elseif (! $report->isSuccess()) {
                    Log::warning('Push delivery failed', [
                        'endpoint' => substr($report->getEndpoint(), 0, 40),
                        'reason' => $report->getReason(),
                    ]);
                }
            }
        } catch (Throwable $exception) {
            Log::warning('Push notification failed', ['error' => $exception->getMessage()]);
        }
    }
}
