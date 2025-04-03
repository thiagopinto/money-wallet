<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
    case FAILED = 'failed';
    case DELIVERED = 'delivered';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendente',
            self::SENT => 'Enviada',
            self::FAILED => 'Falhou',
            self::DELIVERED => 'Entregue',
        };
    }
}