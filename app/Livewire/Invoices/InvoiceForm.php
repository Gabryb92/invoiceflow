<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class InvoiceForm extends Component
{

    public ?Invoice $invoice;

    public $client_id;
    public $invoice_number = '';
    public $issue_date;
    public $due_date;
    public $invoiceitems = [];
    public $subtotal = 0.00;
    public $vat_amount = 0.00;

    public $discount_amount = 0.00;

    public $shipping_amount = 0.00;
    public $total = 0.00;

    public $status = 'unpaid';

    public $notes = '';


    public function addInvoiceItem()
    {
        $this->invoiceitem[] = [
            'description' => '',
            'quantity' => 1,
            'unit_price' => 0.00,
            'vat_rate' => 22.00,
        ];
    }

    public function removeInvoiceItem($index){
        unset($this->invoiceitems[$index]);
        $this->invoiceitems = array_values($this->invoiceitems);
    }

    public function render()
    {
        return view('livewire.invoices.invoice-form')->layout('layouts.app');
    }
}
