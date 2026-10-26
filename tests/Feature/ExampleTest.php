<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_login_page_is_reachable(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('name="_token"', false);
    }
}
