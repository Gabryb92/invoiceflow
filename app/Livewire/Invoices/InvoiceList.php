<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';


    public function render()

    {
    $invoices = Invoice::with('client')->latest()->paginate(10);

        return view('livewire.invoices.invoice-list',compact('invoices'))->layout('layouts.app');
    }
}
