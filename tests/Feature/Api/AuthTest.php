<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_protected_routes()
    {
        $response = $this->postJson('/api/companies', [
            'name' => 'Test',
            'description' => 'desc',
            'location' => 'loc',
        ]);
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_company()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->postJson('/api/companies', [
            'name' => 'Test',
            'description' => 'desc',
            'location' => 'loc',
        ]);
        $response->assertStatus(201);
    }
}
