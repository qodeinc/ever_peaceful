<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'therapist_id',
        'type',
        'severity',
        'title',
        'message',
        'is_read',
        'action_required',
        'action_taken',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'action_required' => 'boolean',
            'action_taken' => 'boolean',
        ];
    }

    /**
     * Relationships
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class);
    }
}
