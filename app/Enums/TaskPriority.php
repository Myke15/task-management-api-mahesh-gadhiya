<?php

namespace App\Enums;

enum TaskPriority : string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    /**
     * Get all enum values as an array.
     *
     * @return list<string>
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    } 
}
