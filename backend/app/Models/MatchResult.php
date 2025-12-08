<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchResult extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'assessment_result_id',
        'therapist_id',
        'match_score',
        'rank',
        'matching_factors',
        'is_viewed',
        'viewed_at',
        'is_contacted',
        'contacted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'match_score' => 'decimal:2',
            'rank' => 'integer',
            'matching_factors' => 'array',
            'is_viewed' => 'boolean',
            'viewed_at' => 'datetime',
            'is_contacted' => 'boolean',
            'contacted_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessmentResult(): BelongsTo
    {
        return $this->belongsTo(AssessmentResult::class);
    }

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class);
    }
}
