<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class InvoiceShow extends Component
{
    public Invoice $invoice;

    public function mount(Invoice $invoice){
        $this->invoice = $invoice;

        $this->invoice->load('client','invoiceItems');
    }
    public function render()
    {
        return view('livewire.invoices.invoice-show')->layout('layouts.app');
    }
}
