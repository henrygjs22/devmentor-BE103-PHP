<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */       
    public function test_the_application_returns_a_successful_response(): void
    {
        Event::factory(5)->create();
        $response = $this->get('api/events');

        $response->assertStatus(200);

        $response->assertJsonCount(2);
        $response->assertJsonPath('meta.last_page', 3);
    }
}