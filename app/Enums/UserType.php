<?php

namespace App\Enums;

enum UserType: string
{
    case COMMON = 'common';
    case SHOPKEEPER = 'shopkeeper';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::COMMON => 'Usuário Comum',
            self::SHOPKEEPER => 'Lojista',
        };
    }
}