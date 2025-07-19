<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoicePdfController extends Controller
{
    public function downloadPdf(Invoice $invoice){
        $invoice->load('client', 'invoiceItems');
        $pdf = Pdf::loadView('fatture.pdf',['invoice' => $invoice])
            ->setOptions([
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'debugKeepTemp' => true
            ]);
        return $pdf->download("fattura_$invoice->invoice_number"."_pdf.pdf");
    }
}
