<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'search_term',
        'location',
        'employment_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'filters',
        'results_count',
        'ip_address',
        'user_agent',
        'session_id',
        'user_id',
    ];

    protected $casts = [
        'filters' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
