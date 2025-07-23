<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\Attributes\On;

class InvoiceShow extends Component
{
    public Invoice $invoice;

    #[On('paymentSaved')]
    public function refreshInvoice(){
        $this->invoice->refresh();
    }
    public function mount(Invoice $invoice){
        $this->invoice = $invoice;

        $this->invoice->load('client','invoiceItems');
    }
    public function render()
    {
        return view('livewire.invoices.invoice-show')->layout('layouts.app');
    }
}
