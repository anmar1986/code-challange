<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        $this->call([
            JobSeeder::class,
        ]);
        
        \App\Models\User::factory(20)->create();
        \App\Models\Company::factory(20)->create();
        \App\Models\Job::factory(20)->create();
        \App\Models\SearchLog::factory(20)->create();
    }
}
