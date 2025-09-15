<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiTest extends TestCase
{
    #[Test]
    public function it_returns_main_status_endpoint()
    {
        $response = $this->getJson('/');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'version',
                'timestamp',
                'status'
            ])
            ->assertJson([
                'status' => 'ok'
            ]);
    }

    #[Test]
    public function it_returns_health_check_endpoint()
    {
        $response = $this->getJson('/up');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_serves_swagger_ui_assets()
    {
        $response = $this->get('/api/documentation');

        $response->assertStatus(200);
    }
}
