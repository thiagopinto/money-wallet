<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case REVERSED = 'reversed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendente',
            self::COMPLETED => 'Concluída',
            self::FAILED => 'Falhou',
            self::REVERSED => 'Estornada',
        };
    }
}
