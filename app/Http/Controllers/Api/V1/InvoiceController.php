<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['client', 'invoiceItems'])->paginate();

        return InvoiceResource::collection($invoices);
    }

    /**
     * Store a newly created resource in storage.
     */

    private function generateInvoiceNumber()
    {
       

        $year = now()->year;
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
        return $newInvoiceNumber;

        //Log::info('Generated invoice number: ' . $this->invoice_number);
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate( [
            'client_id' => 'required|exists:clients,id',
            //'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:unpaid,partially_paid,paid,cancelled',

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
        ]);

        $items = $validated['invoiceItems'];

        //Ho bisogno di dividere l'array in due per inserire successivamente gli items
        $invoiceData = Arr::except($validated, ['invoiceItems']);

        $subtotal = 0;
        $totalVat = 0;
        foreach($items as $item){
            $itemSubtotal = 0;
            $itemVat = 0;
            $itemSubtotal += $item['quantity'] * $item['unit_price'];
            $itemVat += $itemSubtotal * ($item['vat_rate'] / 100);
            $subtotal += $itemSubtotal;
            $totalVat += $itemVat; 
        }

        $totals = ["subtotal"=>$subtotal,
                    "vat_amount" => $totalVat,
                    "total" => $subtotal + $totalVat];
        //$total = $subtotal + $totalVat;

        $invoiceNumber = $this->generateInvoiceNumber();
        $invoice = DB::transaction(function () use ($invoiceData, $items, $totals,$invoiceNumber) {
            $newInvoice = Invoice::create([
                "client_id" => $invoiceData['client_id'],
                "invoice_number" => $invoiceNumber,
                "issue_date" => $invoiceData['issue_date'],
                "due_date" => $invoiceData['due_date'],
                "status" => $invoiceData['status'],
                "notes" => $invoiceData['notes'] ?? null,
                "subtotal" => $totals['subtotal'],
                "vat_amount" => $totals['vat_amount'],
                "total" => $totals['total'],
                
            ]);
            $newInvoice->createInvoiceItems($items);

            return $newInvoice;
        });

        
        return new InvoiceResource($invoice)->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'invoiceItems']);

        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate( [
            'client_id' => 'required|exists:clients,id',
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,'.$invoice->id,
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:unpaid,partially_paid,paid,cancelled',

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
        ]);

        $items = $validated['invoiceItems'];

        //Ho bisogno di dividere l'array in due per inserire successivamente gli items
        $invoiceData = Arr::except($validated, ['invoiceItems']);

        $subtotal = 0;
        $totalVat = 0;
        foreach($items as $item){
            $itemSubtotal = 0;
            $itemVat = 0;
            $itemSubtotal += $item['quantity'] * $item['unit_price'];
            $itemVat += $itemSubtotal * ($item['vat_rate'] / 100);
            $subtotal += $itemSubtotal;
            $totalVat += $itemVat; 
        }

        $totals = ["subtotal"=>$subtotal,
                    "vat_amount" => $totalVat,
                    "total" => $subtotal + $totalVat];
        //$total = $subtotal + $totalVat;

        $invoiceNumber = $this->generateInvoiceNumber();
        DB::transaction(function () use ($invoice,$invoiceData, $items, $totals,$invoiceNumber) {
            $invoice->update([
                "client_id" => $invoiceData['client_id'],
                "invoice_number" => $invoiceNumber,
                "issue_date" => $invoiceData['issue_date'],
                "due_date" => $invoiceData['due_date'],
                "status" => $invoiceData['status'], 
                "notes" => $invoiceData['notes'] ?? null,
                "subtotal" => $totals['subtotal'],
                "vat_amount" => $totals['vat_amount'],
                "total" => $totals['total'],
                
            ]);
            $invoice->invoiceItems()->delete();
            $invoice->createInvoiceItems($items);
            $invoice->load('client', 'invoiceItems');

            
        });

        
        return new InvoiceResource($invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);
        $invoice->delete();
        return response()->noContent();
    }
}
