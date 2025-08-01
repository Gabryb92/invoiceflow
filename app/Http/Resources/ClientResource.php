<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'clients',
            'id' => $this->id,
            'attributes' => [
                'tipo' => $this->company_name ? 'Azienda' : 'Privato',
                'nome_completo' => $this->company_name ?? $this->first_name . ' ' . $this->last_name,
                'nome' => $this->first_name,
                'cognome' => $this->last_name,
                'nome_azienda' => $this->company_name,
                'email' => $this->email,
                'telefono' => $this->phone,
                'indirizzo' => $this->address,
                'citta' => $this->city,
                'provincia' => $this->province,
                'cap' => $this->zip_code,
                'paese' => $this->country,
                'p_iva' => $this->vat_number,
                'codice_fiscale' => $this->fiscal_code,
                'note' => $this->notes,
                'cliente_dal' => $this->created_at->format('d/m/Y'),
                'is_anonimizzato' => (bool) $this->is_anonymized, // Cast a booleano per consistenza
            ],
            'links' => [
                'self' => route('api.clients.show', ['client' => $this->id]),
            ],
        ];
    }
}
