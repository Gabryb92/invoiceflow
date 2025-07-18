<?php

namespace App\Models;

use App\Models\Client;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 
        'invoice_number', 
        'issue_date',
        'due_date',
        'subtotal',
        'vat_amount',
        'total',
        'discount_amount',
        'shipping_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
    ];

    //Una fattura appartiene a un cliente e può avere molti articoli di fattura
    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function invoiceItems(){
        return $this->hasMany(InvoiceItem::class);
    }

    public function createInvoiceItems(array $items)
    {
        foreach ($items as $item) {
            // Usa la relazione 'invoiceItems()' per creare ogni singola voce
            $this->invoiceItems()->create($item);
        }
    }
}
