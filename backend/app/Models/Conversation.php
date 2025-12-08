<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
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
        'last_message_at',
        'user_unread_count',
        'therapist_unread_count',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
            'user_unread_count' => 'integer',
            'therapist_unread_count' => 'integer',
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

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Helper Methods
     */

    public function getUnreadCount(string $userType = 'user'): int
    {
        return $userType === 'user' 
            ? $this->user_unread_count 
            : $this->therapist_unread_count;
    }

    public function markAsRead(string $userType = 'user'): void
    {
        if ($userType === 'user') {
            $this->user_unread_count = 0;
        } else {
            $this->therapist_unread_count = 0;
        }
        $this->save();
    }
}
