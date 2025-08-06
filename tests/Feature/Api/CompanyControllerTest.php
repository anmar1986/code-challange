<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_company_and_becomes_owner()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/companies', [
            'name' => 'Test Company',
            'description' => 'A test company',
            'location' => 'Test City',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('companies', ['name' => 'Test Company']);
        $this->assertDatabaseHas('company_owners', [
            'user_id' => $user->id,
            'is_primary' => true
        ]);
    }

    public function test_only_owner_can_update_company()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $company = Company::factory()->create();
        $company->owners()->attach($user->id, ['is_primary' => true]);

        $this->actingAs($user);
        $response = $this->putJson("/api/companies/{$company->id}", [
            'name' => 'Updated',
            'description' => 'desc',
            'location' => 'loc',
        ]);
        $response->assertStatus(200);

        $this->actingAs($other);
        $response = $this->putJson("/api/companies/{$company->id}", [
            'name' => 'Hacked',
            'description' => 'desc',
            'location' => 'loc',
        ]);
        $response->assertStatus(403);
    }

    public function test_only_primary_owner_can_delete_company()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $company = Company::factory()->create();
        $company->owners()->attach($user->id, ['is_primary' => true]);
        $company->owners()->attach($other->id, ['is_primary' => false]);

        $this->actingAs($user);
        $response = $this->deleteJson("/api/companies/{$company->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);

        $company2 = Company::factory()->create();
        $company2->owners()->attach($other->id, ['is_primary' => false]);
        $this->actingAs($other);
        $response = $this->deleteJson("/api/companies/{$company2->id}");
        $response->assertStatus(403);
    }

    public function test_owner_can_add_another_owner()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $company = Company::factory()->create();
        $company->owners()->attach($user->id, ['is_primary' => true]);

        $this->actingAs($user);
        $response = $this->postJson("/api/companies/{$company->id}/add-owner", [
            'user_id' => $other->id
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('company_owners', [
            'company_id' => $company->id,
            'user_id' => $other->id
        ]);
    }

    public function test_non_owner_cannot_add_owner()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $company = Company::factory()->create();
        $this->actingAs($other);
        $response = $this->postJson("/api/companies/{$company->id}/add-owner", [
            'user_id' => $user->id
        ]);
        $response->assertStatus(403);
    }
}
