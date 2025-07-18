<?php

namespace App\Livewire\Invoices;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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


    public function mount(Invoice $invoice){
        $this->invoice = $invoice;

        if($this->invoice->exists){
            $this->client_id = $this->invoice->client_id;
            $this->invoice_number = $this->invoice->invoice_number;
            $this->issue_date = $this->invoice->issue_date->format('Y-m-d');
            $this->due_date = $this->invoice->due_date->format('Y-m-d');
            $this->notes = $this->invoice->notes;
            $this->status = $this->invoice->status;

            // Carica gli elementi della fattura
            $this->invoiceItems = $this->invoice->invoiceItems->toArray();
        }
    }


    public function rules(){
        $invoiceId = $this->invoice?->id;

        return [
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $invoiceId,
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:unpaid,partially_paid,paid,cancelled',
            "shipping_amount" => 'nullable|numeric|min:0',
            "discount_amount" => 'nullable|numeric|min:0',

            //Regola che controlla se i prodotti sono stati aggiunti
            'invoiceItems' => 'required|array|min:1',

            //Regole per le voci fattura
            "invoiceItems.*.description" => 'required|string|max:255',
            "invoiceItems.*.quantity" => 'required|numeric|min:1',
            "invoiceItems.*.unit_price" => 'required|numeric|min:0',
            "invoiceItems.*.vat_rate" => 'required|numeric|min:0',
        ];
    }

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


    public function save(){

        $this->validate();
        try {



            //dd($this->all());

            
            if ($this->invoice->exists) {
            //Visto che stiamo modificando due entità (la fattura e i suoi articoli), dobbiamo usare una transazione per garantire che entrambe le operazioni siano atomiche
                //Se la fattura esiste, aggiorniamo i dati
                DB::transaction(function () {
                        $this->invoice->update([
                        'client_id' => $this->client_id,
                        'invoice_number' => $this->invoice_number,
                        'issue_date' => $this->issue_date,
                        'due_date' => $this->due_date,
                        'notes' => $this->notes,
                        'status' => $this->status,
                        'shipping_amount' => $this->shipping_amount,
                        'discount_amount' => $this->discount_amount,
                        "subtotal" => $this->calculatedTotals['subtotal'],
                        "vat_amount" => $this->calculatedTotals['vat_amount'],
                        "total" => $this->calculatedTotals['total'],
                    ]);

                    //Usiamo il delete and create, cancelliamo tutte le voci e le reinseriamo!
                    $this->invoice->invoiceItems()->delete();
                    $this->invoice->createInvoiceItems($this->invoiceItems);

                    session()->flash('message', 'Fattura aggiornata con successo.');
                });
                

            }else{
            //Visto che stiamo salvando due entità (la fattura e i suoi articoli), dobbiamo usare una transazione per garantire che entrambe le operazioni siano atomiche
            DB::transaction(function () {
                //Crea una fattura con i dati validati, e subito dopo crea gli articoli della fattura usando il metodo createInvoiceItems dichiarato nel modello Invoice
                Invoice::create([
                    'client_id' => $this->client_id,
                    'invoice_number' => $this->invoice_number,
                    'issue_date' => $this->issue_date,
                    'due_date' => $this->due_date,
                    'notes' => $this->notes,
                    'status' => $this->status,
                    'shipping_amount' => $this->shipping_amount,
                    'discount_amount' => $this->discount_amount,
                    "subtotal" => $this->calculatedTotals['subtotal'],
                    "vat_amount" => $this->calculatedTotals['vat_amount'],
                    "total" => $this->calculatedTotals['total'],
                ])->createInvoiceItems($this->invoiceItems);
            });

                session()->flash('message', 'Fattura salvata con successo!');
                $this->reset();
                // Riporta il componente allo stato di "creazione" pulito
                $this->invoice = new Invoice();
                
                //Resettiamo i prezzi svuotando manualmente la cache della proprietà calcolata.
                unset($this->calculatedTotals);
            }

        } catch (\Exception $e) {
            // Gestione dell'errore, ad esempio log o messaggio di errore
            session()->flash('error', 'Errore durante il salvataggio della fattura:');
            Log::error('Errore durante il salvataggio della fattura', [
                'message' => $e->getMessage(),
                'invoice_data' => $this->all(),
            ]);
            return;
        }
        
    }

    public function render()
    {
        $products = Product::orderBy('name')->get();
        $clients = Client::orderBy('company_name')->orderBy('last_name')->get();
        return view('livewire.invoices.invoice-form',compact('products','clients'))->layout('layouts.app');
    }
}
