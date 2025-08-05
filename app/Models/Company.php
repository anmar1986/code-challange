<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'website',
        'industry',
        'size',
        'founded_year',
        'logo_url',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
        'founded_year' => 'integer',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function owners()
    {
        return $this->belongsToMany(User::class, 'company_owners')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryOwner()
    {
        return $this->belongsToMany(User::class, 'company_owners')
                    ->wherePivot('is_primary', true)
                    ->withPivot('is_primary')
                    ->withTimestamps()
                    ->first();
    }
}
