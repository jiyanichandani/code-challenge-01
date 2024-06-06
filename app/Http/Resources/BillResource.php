<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
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
            'bill_reference' => $this->bill_reference,
            'bill_date' => $this->bill_date,
            'stage' => [
                'label' => $this->stage->label,
                'color_name' => $this->stage->color_name,
            ],
        ];
    }
}
