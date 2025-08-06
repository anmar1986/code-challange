<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\SearchLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_logs_are_created_with_user_info()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->postJson('/api/search', [
            'search_term' => 'Developer',
            'location' => 'Vienna',
        ], [
            'User-Agent' => 'TestAgent',
            'REMOTE_ADDR' => '127.0.0.1',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('search_logs', [
            'search_term' => 'Developer',
            'location' => 'Vienna',
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_search_logs_are_created_with_ip_and_agent()
    {
        $response = $this->postJson('/api/search', [
            'search_term' => 'PHP',
            'location' => 'Remote',
        ], [
            'User-Agent' => 'GuestAgent',
            'REMOTE_ADDR' => '8.8.8.8',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('search_logs', [
            'search_term' => 'PHP',
            'location' => 'Remote',
            'user_agent' => 'GuestAgent',
            'ip_address' => '8.8.8.8',
        ]);
    }
}
