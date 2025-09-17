<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Payment;
use App\Models\InvoiceItem;
use App\Policies\InvoicePolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[UsePolicy(InvoicePolicy::class)]
class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'type', 
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

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function createInvoiceItems(array $items)
    {
        foreach ($items as $item) {
            // Usa la relazione 'invoiceItems()' per creare ogni singola voce
            $this->invoiceItems()->create($item);
        }
    }


    public function getStatusBadgeClasses(): string
    {
        if ($this->type === 'invoice') {
            return match ($this->status) {
                'paid'           => 'text-green-800 bg-green-100 dark:text-green-100 dark:bg-green-500/30',
                'partially_paid' => 'text-yellow-800 bg-yellow-100 dark:text-yellow-100 dark:bg-yellow-500/30',
                'cancelled'      => 'text-gray-800 bg-gray-100 dark:text-gray-100 dark:bg-gray-500/30',
                default          => 'text-red-800 bg-red-100 dark:text-red-100 dark:bg-red-500/30',
            };
        }

        // Se il tipo è 'quote'
        return match ($this->status) {
            'accepted' => 'text-green-800 bg-green-100 dark:text-green-900 dark:bg-green-300',
            'rejected' => 'text-red-800 bg-red-100 dark:text-red-900 dark:bg-red-300',
            'sent'     => 'text-yellow-800 bg-yellow-100 dark:text-yellow-900 dark:bg-yellow-300',
            default    => 'text-gray-800 bg-gray-100 dark:text-gray-900 dark:bg-gray-300',
        };
    }

    /**
     * Restituisce il testo leggibile per lo stato della fattura/preventivo.
     * @return string
     */
    public function getStatusText(): string
    {
        if ($this->type === 'invoice') {
            return match ($this->status) {
                'paid'           => __('Paid'),
                'partially_paid' => __('Partially Paid'),
                'cancelled'      => __('Cancelled'),
                default          => __('Unpaid'),
            };
        }

        // Se il tipo è 'quote'
        return match ($this->status) {
            'accepted' => __('Accepted'),
            'rejected' => __('Rejected'),
            'sent'     => __('Sent'),
            default    => __('Draft'),
        };
    }
    
}
