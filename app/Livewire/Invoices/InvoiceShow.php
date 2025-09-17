<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\Attributes\On;

class InvoiceShow extends Component
{
    public Invoice $invoice;

    public float $amountDue = 0;

    public float $amountPaid = 0;

    



    #[On('paymentSaved')]
    public function refreshInvoice(string $message){
        //Messaggio dal form Payment

        session()->flash('message', $message);

        $this->invoice->refresh();
    }
    public function mount(Invoice $invoice){
        $this->invoice = $invoice;

        $this->invoice->load('client','invoiceItems');
    }
    public function render()
    {
        $this->amountPaid = $this->invoice->payments()->sum('amount_paid');
        $this->amountDue = $this->invoice->total - $this->amountPaid;
        return view('livewire.invoices.invoice-show')->layout('layouts.app');
    }
}
