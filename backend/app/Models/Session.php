<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Session extends Model
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
        'status',
        'scheduled_at',
        'started_at',
        'ended_at',
        'duration_minutes',
        'meeting_url',
        'meeting_id',
        'price',
        'payment_status',
        'payment_id',
        'cancelled_at',
        'cancellation_reason',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'price' => 'decimal:2',
            'duration_minutes' => 'integer',
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

    public function sessionNotes(): HasMany
    {
        return $this->hasMany(SessionNote::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class);
    }

    /**
     * Helper Methods
     */

    public function isUpcoming(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_at > now();
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['scheduled', 'confirmed']) 
            && $this->scheduled_at > now()->addHours(24);
    }
}
