<?php

namespace App\Enums;

enum ProjectStatus : string 
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    } 
}