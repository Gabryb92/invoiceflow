<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'invoices',
            'id' => $this->id,
            'attributes' => [
                "cliente" => new ClientResource($this->whenLoaded('client')),
                "invoice_number" => $this->invoice_number,
                "issue_date" => $this->issue_date->format('Y-m-d'),
                "due_date" => $this->due_date->format('Y-m-d'),
                "subtotal" => (float)$this->subtotal,
                "vat_amount" => (float)$this->vat_amount,
                "total" => (float)$this->total,
                "status" => $this->status,
                "notes" => $this->notes,
                'items' => InvoiceItemResource::collection($this->whenLoaded('invoiceItems')),
                "created_at" => $this->created_at ? $this->created_at->format('Y-m-d') : null,
                "deleted_at" => $this->deleted_at ? $this->deleted_at->format('Y-m-d'): null,
                "updated_at" => $this->updated_at ? $this->updated_at->format('Y-m-d'): null,
                
            ],
            'links' => [
                'self' => route('api.invoices.show', ['invoice' => $this->id]),
            ]
        ];
    }
}
