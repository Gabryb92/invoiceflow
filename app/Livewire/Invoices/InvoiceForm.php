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
        } else {
            $this->generateInvoiceNumber();
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
            // "shipping_amount" => 'nullable|numeric|min:0',
            // "discount_amount" => 'nullable|numeric|min:0',

            //Regola che controlla se i prodotti sono stati aggiunti
            'invoiceItems' => 'required|array|min:1',

            //Regole per le voci fattura
            "invoiceItems.*.description"=> [
                'required',
                'string',
                'max:255',
            ],
            "invoiceItems.*.quantity" => 'required|numeric|min:1',
            "invoiceItems.*.unit_price" => [
                'required',
                'numeric',
            ],
            "invoiceItems.*.vat_rate" => 'required|numeric|min:0',
        ];
    }


    // public function generateInvoiceNumber()
    // {
    //     if ($this->invoice->exists && !empty($this->invoice->invoice_number)) {
    //         $this->invoice_number = $this->invoice->invoice_number;
    //         return;
    //     }

    //     $year = date('Y');
    //     $prefix = "FATT-{$year}-";

    //     // Trova l'ultimo numero usato per quell'anno
    //     $lastInvoice = Invoice::whereYear('issue_date', $year)
    //         ->where('invoice_number', 'like', "$prefix%")
    //         ->orderBy('invoice_number', 'desc')
    //         ->first();

    //     $nextNumber = 1;

    //     if ($lastInvoice) {
    //         $parts = explode('-', $lastInvoice->invoice_number);
    //         if (count($parts) === 3) {
    //             $lastNumber = (int) end($parts);
    //             $nextNumber = $lastNumber + 1;
    //         }
    //     }

    //     // Verifica che il numero non esista già (loop nel raro caso di buco)
    //     do {
    //         $newInvoiceNumber = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    //         $exists = Invoice::where('invoice_number', $newInvoiceNumber)->exists();
    //         $nextNumber++;
    //     } while ($exists);

    //     $this->invoice_number = $newInvoiceNumber;

    //     //Log::info('Generated invoice number: ' . $this->invoice_number);
    // }



    public function generateInvoiceNumber()
    {
        // Se la fattura esiste ed ha già un numero, usalo
        if ($this->invoice->exists && !empty($this->invoice->invoice_number)) {
            $this->invoice_number = $this->invoice->invoice_number;
            return;
        }

        $year = date('Y');
        $prefix = "FATT-{$year}-";

        // Trova l'ultima fattura dell'anno
        $lastInvoice = Invoice::whereYear('issue_date', $year)
            ->where('invoice_number', 'like', "$prefix%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        $nextNumber = 1;

        // Se c'è una fattura, prendi l'ultima parte numerica e somma 1
        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice->invoice_number);
            if (count($parts) === 3) {
                $lastNumber = (int) end($parts);
                $nextNumber = $lastNumber + 1;
            }
        }

        // Controlla che il numero generato non esista già (in caso ci siano buchi)
        do {
            $newInvoiceNumber = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $exists = Invoice::where('invoice_number', $newInvoiceNumber)->exists();
            $nextNumber++;
        } while ($exists);

        // Assegna il numero finale
        $this->invoice_number = $newInvoiceNumber;

        //Log::info('Generated invoice number: ' . $this->invoice_number);
    }

    public function addDiscountItem(){
        $this->invoiceItems[] = 
            [
                'description' => 'Sconto',
                'quantity' => 1,
                'unit_price' => 0.00, 
                'vat_rate' => 0,    // Gli sconti non hanno IVA
                'product_id' => null,
                'type' => 'discount'
            ];
    }

    public function addShippingItem(){
        $this->invoiceItems[] = 
            [
                'description' => 'Spese di spedizione',
                'quantity' => 1,
                'unit_price' => 0.00, 
                'vat_rate' => 22.00,
                'product_id' => null,
                'type' => 'shipping'
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


    public function updatedInvoiceItems()
    {
        // Usiamo il riferimento (&) per modificare l'array originale
        foreach ($this->invoiceItems as &$item) {
            
            $description = $item['description'] ?? '';
            
            // Convertiamo il prezzo in un numero (float) subito all'inizio.
            // Questo trasforma la stringa vuota '' nel numero 0.0.
            $unitPrice = (float)($item['unit_price'] ?? 0);

            if ($description === 'Sconto') {
                $item['vat_rate'] = 0;
                // Usiamo la variabile numerica $unitPrice per il confronto
                if ($unitPrice > 0) {
                    $item['unit_price'] = $unitPrice * -1;
                }
            } 
            elseif ($description === 'Spese di spedizione') {
                $item['vat_rate'] = 22.00;
                // Usiamo la variabile numerica $unitPrice per il confronto
                if ($unitPrice < 0) {
                    // Usiamo la variabile numerica $unitPrice per il calcolo
                    $item['unit_price'] = abs($unitPrice);
                }
            }
        }
    }

    #[Computed]
    // public function calculatedTotals(){
    //     // $subtotal: La somma di tutti gli imponibili (prezzo senza IVA).
    //     // $vatAmount: La somma di tutta l'IVA calcolata.
    //     // $total: Il totale finale della fattura.
    //     $subtotal = 0;
    //     $vatAmount = 0;
        

    //     //Cicliamo ogni prodotto
    //     $temporaryInvoiceItems = collect($this->invoiceItems);
    //     foreach($temporaryInvoiceItems as $item){

    //         $quantity = (float)($item['quantity'] ?? 0);
    //         $unitPrice = (float)($item['unit_price'] ?? 0);
    //         $vatRate = (float)($item['vat_rate'] ?? 0);

    //         //Calcoliamo l'imponibile solo per questa riga
    //         //$productSubTotal = $item['quantity'] * $item['unit_price'];
    //         $productSubTotal = $quantity * $unitPrice;

    //         //Calcoliamo l'IVA solo per questa riga
    //         //$productVat = $productSubTotal * ($item['vat_rate'] / 100);
    //         $productVat = $productSubTotal * ($vatRate / 100);

    //         //Aggiorniamo i totali
    //         $subtotal += $productSubTotal;
    //         $vatAmount += $productVat;

    //     }

        

    //     //Calcoliamo il totale finale includendo eventuali sconti e spese di spedizione usando le proprietà pubbliche della classe
    //     $total = $subtotal + $vatAmount;

    //     //Restituiamo i totali calcolati
    //     return [
    //         'subtotal' => $subtotal,
    //         'vat_amount' => $vatAmount,
    //         'total' => $total,
    //     ];
    // }


    // public function save(){

    //     $this->validate();
    //     try {



    //         //dd($this->all());

            
    //         if ($this->invoice->exists) {
    //         //Visto che stiamo modificando due entità (la fattura e i suoi articoli), dobbiamo usare una transazione per garantire che entrambe le operazioni siano atomiche
    //             //Se la fattura esiste, aggiorniamo i dati
    //             DB::transaction(function () {
    //                     $this->invoice->update([
    //                     'client_id' => $this->client_id,
    //                     'invoice_number' => $this->invoice_number,
    //                     'issue_date' => $this->issue_date,
    //                     'due_date' => $this->due_date,
    //                     'notes' => $this->notes,
    //                     'status' => $this->status,
    //                     "subtotal" => $this->calculatedTotals['subtotal'],
    //                     "vat_amount" => $this->calculatedTotals['vat_amount'],
    //                     "total" => $this->calculatedTotals['total'],
    //                 ]);

    //                 //Usiamo il delete and create, cancelliamo tutte le voci e le reinseriamo!
    //                 $this->invoice->invoiceItems()->delete();
    //                 $this->invoice->createInvoiceItems($this->invoiceItems);

    //                 session()->flash('message', 'Fattura aggiornata con successo.');
    //             });
                

    //         }else{
    //         //Visto che stiamo salvando due entità (la fattura e i suoi articoli), dobbiamo usare una transazione per garantire che entrambe le operazioni siano atomiche
    //         DB::transaction(function () {
    //             //Crea una fattura con i dati validati, e subito dopo crea gli articoli della fattura usando il metodo createInvoiceItems dichiarato nel modello Invoice
    //             Invoice::create([
    //                 'client_id' => $this->client_id,
    //                 'invoice_number' => $this->invoice_number,
    //                 'issue_date' => $this->issue_date,
    //                 'due_date' => $this->due_date,
    //                 'notes' => $this->notes,
    //                 'status' => $this->status,
    //                 'shipping_amount' => $this->shipping_amount,
    //                 'discount_amount' => $this->discount_amount,
    //                 "subtotal" => $this->calculatedTotals['subtotal'],
    //                 "vat_amount" => $this->calculatedTotals['vat_amount'],
    //                 "total" => $this->calculatedTotals['total'],
    //             ])->createInvoiceItems($this->invoiceItems);
    //         });

    //             session()->flash('message', 'Invoice saved correctly');
    //             $this->reset();
    //             // Riporta il componente allo stato di "creazione" pulito
    //             $this->invoice = new Invoice();
                
    //             //Resettiamo i prezzi svuotando manualmente la cache della proprietà calcolata.
    //             unset($this->calculatedTotals);
    //         }

    //     } catch (\Exception $e) {
    //         // Gestione dell'errore, ad esempio log o messaggio di errore
    //         session()->flash('error', "An error occurred while saving, please try again later.");
    //         Log::error('Errore durante il salvataggio della fattura', [
    //             'message' => $e->getMessage(),
    //             'invoice_data' => $this->all(),
    //         ]);
    //         return;
    //     }
        
    // }

    
    
    public function calculatedTotals(){
        $subtotal = 0;
        $vatAmount = 0;
        
        // Debug: aggiungi log per vedere cosa succede
        //Log::info('Calculating totals for items:', $this->invoiceItems);

        foreach($this->invoiceItems as $item){
            // Converti in float con controllo più robusto
            $quantity = is_numeric($item['quantity'] ?? 0) ? (float)$item['quantity'] : 0;
            $unitPrice = is_numeric($item['unit_price'] ?? 0) ? (float)$item['unit_price'] : 0;
            $vatRate = is_numeric($item['vat_rate'] ?? 0) ? (float)$item['vat_rate'] : 0;

            // Debug: log dei valori per ogni item
            // Log::info('Item calculation:', [
            //     'description' => $item['description'] ?? 'N/A',
            //     'quantity' => $quantity,
            //     'unitPrice' => $unitPrice,
            //     'vatRate' => $vatRate
            // ]);

            // Calcola l'imponibile per questa riga
            $productSubTotal = $quantity * $unitPrice;

            // Calcola l'IVA per questa riga
            $productVat = $productSubTotal * ($vatRate / 100);

            // Aggiorna i totali
            $subtotal += $productSubTotal;
            $vatAmount += $productVat;

            // Debug: log dei totali parziali
            // Log::info('Partial totals:', [
            //     'productSubTotal' => $productSubTotal,
            //     'productVat' => $productVat,
            //     'runningSubtotal' => $subtotal,
            //     'runningVatAmount' => $vatAmount
            // ]);
        }

        // Calcola il totale finale
        $total = $subtotal + $vatAmount;

        // Debug: log dei totali finali
        // Log::info('Final calculated totals:', [
        //     'subtotal' => $subtotal,
        //     'vat_amount' => $vatAmount,
        //     'total' => $total,
        // ]);

        return [
            'subtotal' => round($subtotal, 2),
            'vat_amount' => round($vatAmount, 2),
            'total' => round($total, 2),
        ];
    }

    

    public function save(){
        try {
            // Debug: log inizio metodo
            //Log::info('Save method called');
            
            $this->validate();
            
            // Debug: log validazione superata
            //Log::info('Validation passed');
            
            // Ottieni i totali calcolati
            $totals = $this->calculatedTotals;
            
            //Log::info('Calculated totals', $totals);

            if ($this->invoice->exists) {
                // Modifica fattura esistente
                DB::transaction(function () use ($totals) {
                    $this->invoice->update([
                        'client_id' => $this->client_id,
                        'invoice_number' => $this->invoice_number,
                        'issue_date' => $this->issue_date,
                        'due_date' => $this->due_date,
                        'notes' => $this->notes,
                        'status' => $this->status,
                        'subtotal' => $totals['subtotal'],
                        'vat_amount' => $totals['vat_amount'],
                        'total' => $totals['total'],
                    ]);

                    // Elimina e ricrea gli items
                    $this->invoice->invoiceItems()->delete();
                    $this->invoice->createInvoiceItems($this->invoiceItems);

                    //Log::info('Invoice updated successfully');
                });
                
                session()->flash('message', 'Fattura aggiornata con successo.');
                
            } else {
                // Crea nuova fattura
                DB::transaction(function () use ($totals) {
                    $invoice = Invoice::create([
                        'client_id' => $this->client_id,
                        'invoice_number' => $this->invoice_number,
                        'issue_date' => $this->issue_date,
                        'due_date' => $this->due_date,
                        'notes' => $this->notes,
                        'status' => $this->status,
                        'subtotal' => $totals['subtotal'],
                        'vat_amount' => $totals['vat_amount'],
                        'total' => $totals['total'],
                    ]);
                    
                    $invoice->createInvoiceItems($this->invoiceItems);
                    
                    //Log::info('New invoice created successfully');
                });

                session()->flash('message', 'Invoice saved correctly');
                
                // Reset completo del componente
                $this->reset([
                    'client_id', 'invoice_number', 'issue_date', 'due_date', 
                    'invoiceItems', 'subtotal', 'vat_amount', 'total', 'status', 'notes'
                ]);
                
                // Reset esplicito della cache della proprietà computed
                unset($this->calculatedTotals);
                
                // Riporta il componente allo stato di "creazione" pulito
                $this->invoice = new Invoice();
                $this->generateInvoiceNumber();
                
                // Forza il re-render del componente
                $this->dispatch('$refresh');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'invoice_data' => $this->all(),
            ]);
            // Rilancia l'eccezione per mostrare gli errori di validazione
            throw $e;
            
        } catch (\Exception $e) {
            // Gestione dell'errore generico
            session()->flash('error', "An error occurred while saving, please try again later.");
            Log::error('Errore durante il salvataggio della fattura', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'invoice_data' => $this->all(),
            ]);
            return;
        }
    }

    public function render()
    {
        //dd(Product::orderBy('name')->toSql());
        $products = Product::orderBy('name')->get();
        $clients = Client::orderBy('company_name')->orderBy('last_name')->get();
        return view('livewire.invoices.invoice-form',compact('products','clients'))->layout('layouts.app');
    }
}
