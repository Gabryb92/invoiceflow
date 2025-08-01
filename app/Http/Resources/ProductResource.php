<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'products',
            'id' => $this->id,
            'attributes' => [
                "name" => $this->name,
                "description" => $this->description,
                "default_unit_price" => (float) $this->default_unit_price,
                "default_vat_rate" => (float) $this->default_vat_rate,
                "archived_at" => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            ],
        ];
    }
}
