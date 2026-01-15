<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CoreSetupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function csrf_cookie_route_returns_set_cookie_header()
    {
        $response = $this->get('/sanctum/csrf-cookie');

        $response->assertStatus(204);
        $this->assertTrue($response->headers->has('set-cookie'));
    }

    /** @test */
    public function api_routes_return_json_errors_for_not_found()
    {
        $response = $this->getJson('/api/non-existent-route');

        $response->assertStatus(404)
                 ->assertJsonFragment([
                     'status' => 'error',
                     'message' => 'Resource not found',
                 ]);
    }
}
