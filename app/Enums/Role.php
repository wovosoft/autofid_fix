<?php

namespace App\Enums;

enum Role: string
{
    case SuperAdmin = 'super_admin';
    case Agent = "agent";
    case Customer = "customer";

    public static function values(): array
    {
        return collect(self::cases())->map(fn(self $i) => $i->value)->toArray();
    }
}
