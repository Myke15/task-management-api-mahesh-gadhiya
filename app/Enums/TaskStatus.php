<?php

namespace App\Enums;

enum TaskStatus : string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

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
