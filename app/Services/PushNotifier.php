<?php

namespace App\Services;

use App\Models\Alerte;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Throwable;

class PushNotifier
{
    public function alerte(Alerte $alerte): void
    {
        $publicKey = config('webpush.public_key');
        $privateKey = config('webpush.private_key');

        if (! $publicKey || ! $privateKey) {
            return;
        }

        $subscriptions = PushSubscription::query()
            ->where('user_id', '!=', $alerte->user_id)
            ->limit(300)
            ->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        $payload = json_encode([
            'title' => 'Besoin de sang '.$alerte->groupeSanguin->libelle,
            'body' => $alerte->quantite.' poche'.($alerte->quantite > 1 ? 's' : '')
                .' — '.$alerte->ville.($alerte->lieu ? ' ('.$alerte->lieu.')' : ''),
            'url' => route('alertes.show', $alerte),
            'tag' => 'alerte-'.$alerte->id,
        ], JSON_UNESCAPED_UNICODE);

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
                    $payload,
                    ['TTL' => config('webpush.ttl')]
                );
            }

            foreach ($webPush->flush() as $report) {
                if ($report->isSubscriptionExpired()) {
                    PushSubscription::where('endpoint_hash', hash('sha256', $report->getEndpoint()))->delete();
                }
            }
        } catch (Throwable $exception) {
            Log::warning('Push notification failed', ['error' => $exception->getMessage()]);
        }
    }
}
