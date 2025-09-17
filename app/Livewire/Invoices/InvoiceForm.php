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

    public $type = 'invoice';

    public $status = 'unpaid';

    public $notes = '';

    protected $invoiceStatuses = ['unpaid', 'partially_paid', 'paid', 'cancelled'];
    protected $quoteStatuses = ['draft', 'sent', 'accepted', 'rejected', 'cancelled'];

    public function mount(Invoice $invoice){
        $this->invoice = $invoice;

        if($this->invoice->exists){
            $this->client_id = $this->invoice->client_id;
            $this->invoice_number = $this->invoice->invoice_number;
            $this->issue_date = $this->invoice->issue_date->format('Y-m-d');
            $this->due_date = $this->invoice->due_date->format('Y-m-d');
            $this->notes = $this->invoice->notes;
            $this->status = $this->invoice->status;
            $this->type = $this->invoice->type;

            // Carica gli elementi della fattura
            $this->invoiceItems = $this->invoice->invoiceItems->toArray();
        } 
        // Se stiamo creando, controlliamo la rotta per decidere il tipo
        elseif (request()->route()->getName() === 'preventivi.create') {
            $this->type = 'quote';
            $this->status = 'draft'; // Stato di default per un nuovo preventivo
            $this->generateQuoteNumber();
        } 
        else {
            $this->generateInvoiceNumber();
        }
    }


    // public function rules(){
    //     $invoiceId = $this->invoice?->id;

    //     return [
    //         'client_id' => 'required|exists:clients,id',
    //         'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $invoiceId,
    //         'issue_date' => 'required|date',
    //         'due_date' => 'required|date|after_or_equal:issue_date',
    //         'notes' => 'nullable|string|max:1000',
    //         'status' => 'required|in:' . implode(',', $allowedStatuses),
            

    //         //Regola che controlla se i prodotti sono stati aggiunti
    //         'invoiceItems' => 'required|array|min:1',

    //         //Regole per le voci fattura
    //         "invoiceItems.*.description"=> [
    //             'required',
    //             'string',
    //             'max:255',
    //         ],
    //         "invoiceItems.*.quantity" => 'required|numeric|min:1',
    //         "invoiceItems.*.unit_price" => [
    //             'required',
    //             'numeric',
    //         ],
    //         "invoiceItems.*.vat_rate" => 'required|numeric|min:0',
    //     ];
    // }

    

    public function rules()
    {
        $invoiceId = $this->invoice?->id;
        
        // 1. Definiamo le regole comuni a entrambi
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $invoiceId,
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'notes' => 'nullable|string|max:1000',
            'invoiceItems' => 'required|array|min:1',
            "invoiceItems.*.description"=> ['required', 'string', 'max:255'],
            "invoiceItems.*.quantity" => 'required|numeric|min:1',
            "invoiceItems.*.unit_price" => ['required', 'numeric'],
            "invoiceItems.*.vat_rate" => 'required|numeric|min:0',
        ];

        // 2. Aggiungiamo la regola per lo 'status' in modo condizionale
        if ($this->type === 'quote') {
            // Se è un preventivo, può avere solo questi stati
            $rules['status'] = 'required|in:' . implode(',', $this->quoteStatuses);
        } else {
            // Altrimenti (se è una fattura), può avere solo questi altri stati
            $rules['status'] = 'required|in:' . implode(',', $this->invoiceStatuses);
        }

        return $rules;
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


    public function generateQuoteNumber()
    {
        $year = date('Y');
        $prefix = "PREV-{$year}-";

        $lastQuote = Invoice::where('type', 'quote')
            ->whereYear('issue_date', $year)
            ->orderBy('invoice_number', 'desc')
            ->first();
        
        $nextNumber = 1;
        if ($lastQuote) {
            $lastNumber = (int) substr($lastQuote->invoice_number, -4);
            $nextNumber = $lastNumber + 1;
        }
        
        $this->invoice_number = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }



    public function generateInvoiceNumber(bool $force = false)
    {
        // Se la fattura esiste ed ha già un numero, usalo
        if ($this->invoice->exists && !empty($this->invoice->invoice_number) && !$force) {
            $this->invoice_number = $this->invoice->invoice_number;
            return;
        }

        $year = date('Y');
        $prefix = "FATT-{$year}-";

        // Trova l'ultima fattura dell'anno
        $lastInvoice = Invoice::where('type','invoice')
            ->whereYear('issue_date', $year)
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


    public function convertToInvoice()
    {
        if (!$this->invoice->exists || $this->invoice->type !== 'quote') {
            session()->flash('error', 'Azione non permessa.');
            return;
        }

        // Forza la generazione di un NUOVO numero di fattura
        $this->generateInvoiceNumber(true);


        // Aggiorna il record esistente
        $this->invoice->update([
            'type'           => 'invoice',
            'status'         => 'unpaid',
            'issue_date'     => now(),
            'due_date'       => now()->addDays(30),
            'invoice_number' => $this->invoice_number,
        ]);

        
        
        // Ricarica la pagina per vedere i cambiamenti
        return redirect()->route('fatture.edit', $this->invoice)->with('message', 'Preventivo convertito in fattura con successo!');;
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
                        'type' => $this->type,
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
                        'type' => $this->type,
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
                    'invoiceItems', 'subtotal', 'vat_amount', 'total', 'status', 'notes','type',
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
