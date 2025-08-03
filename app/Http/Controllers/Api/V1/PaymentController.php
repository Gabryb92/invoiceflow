<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Invoice $invoice)
    {
        $validated = $request->validate([
            "amount_paid" => "required|numeric",
            "payment_date" => "required|date",
            "payment_method" => "string",
            "notes" => "string",
        ]);

        
            DB::transaction(function() use ($invoice,$validated) {
                $invoice->payments()->create($validated);
                $totalPaid = $invoice->payments()->sum('amount_paid');
                if($totalPaid>= $invoice->total){
                    //Significa che è stata saldata
                    $invoice->update([
                        "status" => "paid"
                    ]);
                } else if ($totalPaid > 0) {
                    $invoice->update([
                        "status" => "partially_paid"
                    ]);
                }
                
            });

        $invoice->load('client', 'invoiceItems');
        return new InvoiceResource($invoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
