<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'employment_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'salary_currency',
        'requirements',
        'benefits',
        'expires_at',
        'is_active',
        'company_id',
        'posted_by',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'expires_at' => 'date',
        'is_active' => 'boolean',
    ];

    # check if the job have a company 
        public function company()
    {
        return $this->belongsTo(Company::class);
    }

    # check if the job was posted by a user
    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    
    public function scopeActive($query)
    {
        // get only the is_active jobs
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    // search for a job by title or location
    public function scopeSearch($query, $term, $location = null)
    {
        $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });

        if ($location) {
            $query->where('location', 'like', "%{$location}%");
        }

        return $query;
    }
}
