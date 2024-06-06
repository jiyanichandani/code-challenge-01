<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_bills' => $this->total_bills,
            'submitted_bills' => $this->submitted_bills,
            'approved_bills' => $this->approved_bills,
            'bills' => BillResource::collection($this->whenLoaded('bills')),
        ];
    }
}
