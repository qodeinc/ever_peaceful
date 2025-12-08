<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'therapist_id',
        'amount',
        'platform_fee',
        'net_amount',
        'period_start',
        'period_end',
        'status',
        'payment_method',
        'transaction_reference',
        'processed_at',
        'processed_by',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'period_start' => 'date',
            'period_end' => 'date',
            'processed_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
