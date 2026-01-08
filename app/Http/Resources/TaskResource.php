<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;

/**
 * @property-read int $id
 * @property-read int $project_id
 * @property-read string $title
 * @property-read string $description
 * @property-read string $status
 * @property-read string $priority
 * @property-read string|null $due_date
 * @property-read string $created_at
 * @property-read string $updated_at
 */
class TaskResource extends BaseResource
{
    public static $wrap = 'task';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'project_id'        => $this->project_id,
            'title'             => $this->title,
            'description'       => $this->description,
            'status'            => $this->status,
            'priority'          => $this->priority,
            'due_date'          => $this->due_date,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }

}
