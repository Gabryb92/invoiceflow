<?php

namespace App\Livewire\Invoices;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;

class InvoiceForm extends Component
{

    public ?Invoice $invoice;

    public $client_id;
    public $invoice_number = '';
    public $issue_date;
    public $due_date;
    public $invoiceItems = [];
    public $subtotal = 0.00;
    public $vat_amount = 0.00;

    public $discount_amount = 0.00;

    public $shipping_amount = 0.00;
    public $total = 0.00;

    public $status = 'unpaid';

    public $notes = '';


    public function addInvoiceItem(Product $product)
    {
        //1 Convertiamo il nostro array in una collezione per poter usare le funzioni di Laravel
        $invoiceItems = collect($this->invoiceItems);
        //2 Usiamo il metodo search per trovare l'indice dell'elemento con lo stesso product_id:
        $index = $invoiceItems->search(function ($item) use ($product) {
            return $item['product_id'] === $product->id;
        });


        //3 Controlliamo il risultato:
        if ($index !== false) {
            // Se l'elemento esiste già, possiamo incrementare la quantità
            $this->invoiceItems[$index]['quantity']++;  
        }else{
            $this->invoiceItems[] = [
                "product_id" => $product->id,
                'description' => $product->name ?? '',
                'quantity' => 1,
                'unit_price' => $product->default_unit_price ?? 0.00,
                'vat_rate' => $product->default_vat_rate ?? 22.00,
            ];
        }

        
    }

    public function removeInvoiceItem($index){
        $items = collect($this->invoiceItems);

        $items->splice($index, 1);

        //dd($items->all());
        $this->invoiceItems = $items->all();
        
    }

    #[Computed]
    public function calculatedTotals(){
        // $subtotal: La somma di tutti gli imponibili (prezzo senza IVA).
        // $vatAmount: La somma di tutta l'IVA calcolata.
        // $total: Il totale finale della fattura.
        $subtotal = 0;
        $vatAmount = 0;
        

        //Cicliamo ogni prodotto
        $temporaryInvoiceItems = collect($this->invoiceItems);
        foreach($temporaryInvoiceItems as $item){
            //Calcoliamo l'imponibile solo per questa riga
            $productSubTotal = $item['quantity'] * $item['unit_price'];

            //Calcoliamo l'IVA solo per questa riga
            $productVat = $productSubTotal * ($item['vat_rate'] / 100);

            //Aggiorniamo i totali
            $subtotal += $productSubTotal;
            $vatAmount += $productVat;

        }

        //Calcoliamo il totale finale includendo eventuali sconti e spese di spedizione usando le proprietà pubbliche della classe
        $total = $subtotal + $vatAmount + $this->shipping_amount - $this->discount_amount;

        //Restituiamo i totali calcolati
        return [
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'total' => $total,
        ];
    }

    public function render()
    {
        $products = Product::orderBy('name')->get();
        $clients = Client::orderBy('company_name')->orderBy('last_name')->get();
        return view('livewire.invoices.invoice-form',compact('products','clients'))->layout('layouts.app');
    }
}
