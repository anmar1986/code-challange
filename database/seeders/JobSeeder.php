<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample user if one doesn't exist
        $user = User::firstOrCreate(
            [
                'email' => 'test@example.com'
            ],
            [
                'name' => 'Test User',
                'password' => Hash::make('password')
            ]
        );

        // Create a sample company
        $company = Company::firstOrCreate(
            [
                'name' => 'Tech Solutions Inc.'
            ],
            [
                'description' => 'A leading technology company specializing in software development.',
                'location' => 'Berlin, Germany',
                'website' => 'https://techsolutions.com',
                'industry' => 'Software',
                'size' => '500-1000',
                'founded_year' => 2005,
                'logo_url' => 'https://via.placeholder.com/150/0000FF/FFFFFF?text=TS',
                'social_links' => json_encode(['linkedin' => 'https://linkedin.com/company/techsolutions'] ),
            ]
        );

        // Attach the user as the primary owner of the company
        if (!$user->ownedCompanies()->where('company_id', $company->id)->exists()) {
            $user->ownedCompanies()->attach($company->id, ['is_primary' => true]);
        }

        // Create sample jobs for the company
        Job::firstOrCreate(
            [
                'title' => 'Senior Software Engineer',
                'company_id' => $company->id,
            ],
            [
                'description' => 'Develop and maintain high-quality software solutions.',
                'location' => 'Berlin, Germany',
                'employment_type' => 'full-time',
                'experience_level' => 'senior',
                'salary_min' => 70000,
                'salary_max' => 90000,
                'salary_currency' => 'EUR',
                'requirements' => '5+ years experience in web development, strong knowledge of Vue.js and Laravel.',
                'benefits' => 'Health insurance, paid time off, remote work options.',
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
                'posted_by' => $user->id,
            ]
        );

        Job::firstOrCreate(
            [
                'title' => 'Junior Frontend Developer',
                'company_id' => $company->id,
            ],
            [
                'description' => 'Assist in developing user-facing features using modern frontend technologies.',
                'location' => 'Remote',
                'employment_type' => 'full-time',
                'experience_level' => 'entry',
                'salary_min' => 35000,
                'salary_max' => 45000,
                'salary_currency' => 'EUR',
                'requirements' => 'Basic understanding of HTML, CSS, JavaScript, and Vue.js.',
                'benefits' => 'Mentorship program, flexible hours.',
                'expires_at' => now()->addMonths(2),
                'is_active' => true,
                'posted_by' => $user->id,
            ]
        );

        Job::firstOrCreate(
            [
                'title' => 'Product Manager',
                'company_id' => $company->id,
            ],
            [
                'description' => 'Define product vision, strategy, and roadmap.',
                'location' => 'Berlin, Germany',
                'employment_type' => 'full-time',
                'experience_level' => 'mid',
                'salary_min' => 60000,
                'salary_max' => 80000,
                'salary_currency' => 'EUR',
                'requirements' => '3+ years in product management, strong communication skills.',
                'benefits' => 'Performance bonus, professional development.',
                'expires_at' => now()->addMonths(4),
                'is_active' => true,
                'posted_by' => $user->id,
            ]
        );
    }
}
