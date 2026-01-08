<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BaseCollection;

class TaskCollection extends BaseCollection
{
    public static $wrap = 'tasks';
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tasks'  => $this->collection
        ];
    }
    
}
