<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionNote extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'session_id',
        'therapist_id',
        'user_id',
        'notes',
        'goals',
        'progress',
        'recommendations',
        'is_confidential',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'goals' => 'array',
            'is_confidential' => 'boolean',
        ];
    }

    /**
     * Relationships
     */

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
