<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Company;
use App\Models\Job;
use App\Models\SearchLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_job()
    {
        $company = Company::factory()->create();
        $response = $this->postJson('/api/jobs', [
            'company_id' => $company->id,
            'title' => 'Test Job',
            'description' => 'desc',
            'location' => 'loc',
            'employment_type' => 'full-time',
        ]);
        $response->assertStatus(401);
    }

    public function test_owner_can_create_job_for_owned_company()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $company->owners()->attach($user->id, ['is_primary' => true]);
        $this->actingAs($user);
        $response = $this->postJson('/api/jobs', [
            'company_id' => $company->id,
            'title' => 'Test Job',
            'description' => 'desc',
            'location' => 'loc',
            'employment_type' => 'full-time',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('jobs', ['title' => 'Test Job', 'company_id' => $company->id]);
    }

    public function test_non_owner_cannot_create_job_for_company()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $company = Company::factory()->create();
        $company->owners()->attach($user->id, ['is_primary' => true]);
        $this->actingAs($other);
        $response = $this->postJson('/api/jobs', [
            'company_id' => $company->id,
            'title' => 'Test Job',
            'description' => 'desc',
            'location' => 'loc',
            'employment_type' => 'full-time',
        ]);
        $response->assertStatus(403);
    }

    public function test_job_list_includes_all_relevant_fields()
    {
        $company = Company::factory()->create();
        $job = Job::factory()->create(['company_id' => $company->id]);
        $response = $this->getJson('/api/jobs');
        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => $job->title,
                'description' => $job->description,
                'location' => $job->location,
                'employment_type' => $job->employment_type,
            ]);
    }
}
