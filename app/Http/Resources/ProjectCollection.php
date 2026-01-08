<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BaseCollection;

class ProjectCollection extends BaseCollection
{
    
    public static $wrap = 'projects';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'projects'  => $this->collection
        ];
    }
}
