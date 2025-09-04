<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoicePdfController extends Controller
{
    public function downloadPdf(Invoice $invoice){

        $invoice->load('client', 'invoiceItems');

        //Convertiamo il logo in Base64
        $logoPath = file_exists('img/logo.png') ? public_path('img/logo.png') : public_path('img/default.png');
        

        $logoData = base64_encode(file_get_contents($logoPath));
        

        $logoBase64 = 'data:image/png;base64,' . $logoData;

        $viewData = ['invoice' => $invoice,'logo'=>$logoBase64];

        
        $pdf = Pdf::loadView('fatture.pdf', $viewData)
            ->setOptions([
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            ]);
        return $pdf->download("fattura_$invoice->invoice_number"."_pdf.pdf");
    }
}
