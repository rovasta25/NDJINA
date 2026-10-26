<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PushSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_subscribe(): void
    {
        $this->postJson(route('push.store'), $this->payload())
            ->assertUnauthorized();
    }

    public function test_authenticated_user_can_subscribe(): void
    {
        $user = User::factory()->create(['city' => 'Libreville']);

        $this->actingAs($user)
            ->postJson(route('push.store'), $this->payload())
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->assertDatabaseCount('push_subscriptions', 1);
        $this->assertDatabaseHas('push_subscriptions', [
            'user_id' => $user->id,
            'endpoint' => $this->payload()['endpoint'],
        ]);
    }

    public function test_same_endpoint_is_updated_not_duplicated(): void
    {
        $user = User::factory()->create(['city' => 'Libreville']);
        $payload = $this->payload();

        $this->actingAs($user)->postJson(route('push.store'), $payload)->assertOk();

        $payload['keys']['auth'] = 'AnotherAuthToken';

        $this->actingAs($user)->postJson(route('push.store'), $payload)->assertOk();

        $this->assertDatabaseCount('push_subscriptions', 1);
        $this->assertDatabaseHas('push_subscriptions', ['auth_token' => 'AnotherAuthToken']);
    }

    public function test_invalid_payload_is_rejected(): void
    {
        $user = User::factory()->create(['city' => 'Libreville']);

        $this->actingAs($user)
            ->postJson(route('push.store'), ['endpoint' => 'not-a-url'])
            ->assertStatus(422);
    }

    public function test_user_can_remove_a_subscription(): void
    {
        $user = User::factory()->create(['city' => 'Libreville']);

        $this->actingAs($user)->postJson(route('push.store'), $this->payload())->assertOk();

        $this->actingAs($user)
            ->deleteJson(route('push.destroy'), ['endpoint' => $this->payload()['endpoint']])
            ->assertOk();

        $this->assertDatabaseCount('push_subscriptions', 0);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/abc-123',
            'keys' => [
                'p256dh' => 'BPublicKeyExample',
                'auth' => 'AuthTokenExample',
            ],
            'contentEncoding' => 'aes128gcm',
        ];
    }
}
