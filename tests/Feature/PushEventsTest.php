<?php

namespace Tests\Feature;

use App\Models\Alerte;
use App\Models\GroupeSanguin;
use App\Models\Reponse;
use App\Models\User;
use App\Services\PushNotifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PushEventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_response_notifies_the_alert_owner(): void
    {
        $notifier = Mockery::spy(PushNotifier::class);
        $this->app->instance(PushNotifier::class, $notifier);

        [$alerte, , $donor] = $this->conversation();

        $this->actingAs($donor)
            ->post(route('reponses.store', $alerte), ['message' => 'Je suis disponible.'])
            ->assertRedirect();

        $this->assertDatabaseHas('reponses', ['alerte_id' => $alerte->id, 'user_id' => $donor->id]);
        $notifier->shouldHaveReceived('reponse')->once();
    }

    public function test_new_message_notifies_the_recipient(): void
    {
        $notifier = Mockery::spy(PushNotifier::class);
        $this->app->instance(PushNotifier::class, $notifier);

        [$alerte, $owner, $donor] = $this->conversation();
        Reponse::create([
            'alerte_id' => $alerte->id,
            'user_id' => $donor->id,
            'statut' => 'en_attente',
        ]);

        $this->actingAs($donor)
            ->post(route('messages.store', [$alerte, $owner]), ['contenu' => 'Je peux venir demain.'])
            ->assertRedirect();

        $this->assertDatabaseHas('messages', [
            'alerte_id' => $alerte->id,
            'expediteur_id' => $donor->id,
            'destinataire_id' => $owner->id,
        ]);
        $notifier->shouldHaveReceived('message')->once();
    }

    public function test_confirmed_response_notifies_the_donor(): void
    {
        $notifier = Mockery::spy(PushNotifier::class);
        $this->app->instance(PushNotifier::class, $notifier);

        [$alerte, $owner, $donor] = $this->conversation();
        $reponse = Reponse::create([
            'alerte_id' => $alerte->id,
            'user_id' => $donor->id,
            'statut' => 'en_attente',
        ]);

        $this->actingAs($owner)
            ->post(route('reponses.confirmer', $reponse))
            ->assertRedirect();

        $this->assertSame('confirmee', $reponse->fresh()->statut);
        $notifier->shouldHaveReceived('reponseConfirmee')->once();
    }

    /**
     * @return array{0: Alerte, 1: User, 2: User}
     */
    private function conversation(): array
    {
        $groupe = GroupeSanguin::create(['libelle' => 'A+']);
        $owner = User::factory()->create(['city' => 'Libreville']);
        $donor = User::factory()->create(['city' => 'Libreville']);

        $alerte = Alerte::create([
            'user_id' => $owner->id,
            'groupe_sanguin_id' => $groupe->id,
            'quantite' => 2,
            'urgence' => 'haute',
            'ville' => 'Libreville',
            'statut' => 'active',
        ]);

        return [$alerte, $owner, $donor];
    }
}
