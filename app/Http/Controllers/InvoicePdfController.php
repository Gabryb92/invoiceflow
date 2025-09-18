<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoicePdfController extends Controller
{
    public function downloadPdf(Invoice $invoice){

        // 1. Definiamo il titolo (maiuscolo) per essere usato DENTRO il PDF
        $documentTitle = $invoice->type === 'invoice' ? 'Fattura' : 'Preventivo';

        // 2. Definiamo una versione minuscola per il NOME DEL FILE
        $documentFileName = $invoice->type === 'invoice' ? 'fattura' : 'preventivo';

        $invoice->load('client', 'invoiceItems');

        //Convertiamo il logo in Base64
        $logoPath = file_exists('img/logo.png') ? public_path('img/logo.png') : public_path('img/default.png');
        

        $logoData = base64_encode(file_get_contents($logoPath));
        

        $logoBase64 = 'data:image/png;base64,' . $logoData;

        $viewData = ['invoice' => $invoice,
            'logo'=>$logoBase64, 
            'documentTitle' => $documentTitle 
        ];

        
        $pdf = Pdf::loadView('fatture.pdf', $viewData)
            ->setOptions([
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            ]);

        $fileName = "{$documentFileName}_{$invoice->invoice_number}.pdf";

        return $pdf->download($fileName);
    }
}
