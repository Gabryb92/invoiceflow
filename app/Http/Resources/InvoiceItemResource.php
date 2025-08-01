<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => 'invoice-items', // Nome del tipo
            'attributes' => [
                'description' => $this->description,
                'quantity' => (float) $this->quantity,
                'unit_price' => (float) $this->unit_price,
                'vat_rate' => (float) $this->vat_rate,
                'subtotal' => round((float) $this->quantity * (float) $this->unit_price,2),
            ]
        ];
    }
}