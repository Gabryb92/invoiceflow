<?php

namespace App\Livewire\Invoices;

use Exception;
use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class InvoiceList extends Component
{
    use WithPagination;

    public $search = "";
    protected $paginationTheme = 'tailwind';


    public function deleteInvoice(int $invoice_id){
        try{

            $invoice = Invoice::findOrFail($invoice_id);
            $invoice->delete();
            session()->flash('message', 'Invoice permanently deleted successfully.');
        } catch (Exception $e) {
            session()->flash('error', "An error occurred during the deletion. Please try again later.");
            Log::error($e->getMessage());
        }
    }

    public function render()
    {

        // Iniziamo la query di base con la relazione pre-caricata
        $query = Invoice::query()->with('client');

        // Applichiamo il filtro di ricerca
        $query->when($this->search, function ($q) {
            $searchTerm = '%' . $this->search . '%';
            
            $q->where(function ($subQuery) use ($searchTerm) {
                // Cerca nelle colonne della tabella 'invoices'
                $subQuery->where('invoice_number', 'like', $searchTerm)
                        //->orWhere('total', 'like', $searchTerm)
                        ->orWhere('status', 'like', $searchTerm);
                
                // Cerca all'interno della relazione 'client'
                $subQuery->orWhereHas('client', function ($clientQuery) use ($searchTerm) {
                    $clientQuery->where('first_name', 'like', $searchTerm)
                                ->orWhere('last_name', 'like', $searchTerm)
                                ->orWhere('company_name', 'like', $searchTerm);
                });
            });
        });

        // Alla fine, ordiniamo e paginiamo
        $invoices = $query->latest()->paginate(10);

        return view('livewire.invoices.invoice-list',compact('invoices'))->layout('layouts.app');
    }
}
