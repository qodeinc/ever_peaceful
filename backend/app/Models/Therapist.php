<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Therapist extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'license_number',
        'specialization',
        'bio',
        'experience_years',
        'hourly_rate',
        'availability',
        'calendly_access_token',
        'calendly_refresh_token',
        'calendly_connected_at',
        'rating',
        'total_sessions',
        'status',
        'approved_at',
        'approved_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'specialization' => 'array',
            'availability' => 'array',
            'calendly_connected_at' => 'datetime',
            'approved_at' => 'datetime',
            'rating' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'experience_years' => 'integer',
            'total_sessions' => 'integer',
        ];
    }

    /**
     * Relationships
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Session::class)->distinct('user_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Helper Methods
     */

    public function isApproved(): bool
    {
        return $this->status === 'active' && $this->approved_at !== null;
    }

    public function getRating(): float
    {
        return (float) $this->rating;
    }
}
