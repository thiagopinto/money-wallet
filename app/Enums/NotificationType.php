<?php

namespace App\Enums;

enum NotificationType: string
{
    case TRANSACTION = 'transaction';
    case SYSTEM = 'system';
    case PROMOTIONAL = 'promotional';
    case SECURITY = 'security';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::TRANSACTION => 'Transação',
            self::SYSTEM => 'Sistema',
            self::PROMOTIONAL => 'Promocional',
            self::SECURITY => 'Segurança',
        };
    }
}