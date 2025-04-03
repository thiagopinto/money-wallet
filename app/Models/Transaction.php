<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payer_id',
        'payee_id',
        'status',
        'authorization_code',
        'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => TransactionStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payee_id');
    }

    public function markAsCompleted(string $authorizationCode = null): void
    {
        $this->update([
            'status' => TransactionStatus::COMPLETED,
            'authorization_code' => $authorizationCode,
        ]);
    }

    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status' => TransactionStatus::FAILED,
            'failure_reason' => $reason,
        ]);
    }

    public function markAsReversed(string $reason = null): void
    {
        $this->update([
            'status' => TransactionStatus::REVERSED,
            'failure_reason' => $reason,
        ]);
    }

    public function isCompleted(): bool
    {
        return $this->status === TransactionStatus::COMPLETED;
    }
}