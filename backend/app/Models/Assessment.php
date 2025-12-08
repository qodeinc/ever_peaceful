<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'instructions',
        'questions',
        'scoring_rules',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'questions' => 'array',
            'scoring_rules' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relationships
     */

    public function results(): HasMany
    {
        return $this->hasMany(AssessmentResult::class);
    }

    /**
     * Helper Methods
     */

    public function isActive(): bool
    {
        return $this->is_active === true;
    }
}
