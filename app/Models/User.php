<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'user_type',
        'balance',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_type' => UserType::class,
        'balance' => 'decimal:2',
    ];

    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'payee_id');
    }

    public function isShopkeeper(): bool
    {
        return $this->user_type === UserType::SHOPKEEPER;
    }

    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}