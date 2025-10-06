<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $documentTitle }} {{ $invoice->invoice_number }}</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');

    :root {
        --font-family: 'Inter', sans-serif;
        --font-color-dark: #1a202c;
        --font-color-light: #718096;
        --primary-color: #3675dd;
        --background-light: #f7fafc;
        --border-color: #e2e8f0;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: var(--font-family);
        font-size: 9.5pt; /* RIDOTTO LEGGERMENTE */
        color: var(--font-color-dark);
        background-color: #fff;
        line-height: 1.5; /* RIDOTTO */
        padding: 1cm 1cm 2cm 1cm; /* RIDOTTO */
    }
    
    .invoice-container { width: 100%; margin: 0 auto; }

    .text-right{
        text-align: right;
    }

    /* RIDOTTI MARGINI TRA LE SEZIONI */
    .invoice-header { display: table; width: 100%; margin-bottom: 25px; }
    .invoice-meta { display: table; width: 100%; margin-bottom: 25px; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); padding: 15px 0; }
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    
    .header-left, .header-right { display: table-cell; vertical-align: middle; }
    .header-left { width: 60%; }
    .header-right { width: 40%; text-align: right; }
    .logo { width: 45px; height: auto; margin-right: 15px; vertical-align: middle; } /* RIDOTTO */
    .company-name { font-size: 15pt; font-weight: 700; display: inline-block; vertical-align: middle; } /* RIDOTTO */
    .document-title { font-size: 18pt; font-weight: 700; color: var(--primary-color); margin: 0; } /* RIDOTTO */
    .document-details p { margin: 2px 0 0 0; font-size: 9.5pt; color: var(--font-color-light); }

    .meta-block { display: table-cell; vertical-align: top; width: 50%; }
    .meta-heading { font-size: 8pt; font-weight: 500; color: var(--font-color-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; } /* RIDOTTO */
    .meta-content { font-size: 9.5pt; line-height: 1.4; } /* RIDOTTO */
    .meta-content strong { font-weight: 500; }
    .meta-block.client-info { padding-left: 30px; text-align: right; }

    /* RIDOTTO PADDING CELLE TABELLA (MOLTO EFFICACE) */
    .items-table th { text-align: left; padding: 8px 10px; background-color: var(--background-light); color: var(--font-color-light); font-size: 8.5pt; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border-color); }
    .items-table td { padding: 8px 10px; border-bottom: 1px solid var(--border-color); vertical-align: top; }
        
    /* Regola per la larghezza delle colonne */
    .items-table th:nth-child(1) { width: 34%; } /* Descrizione */
    .items-table th:nth-child(2) { width: 6%; }  /* U/M */
    .items-table th:nth-child(3) { width: 9%; }  /* Quantità */
    .items-table th:nth-child(4) { width: 13%; } /* Prezzo Unitario */
    .items-table th:nth-child(5) { width: 9%; }  /* IVA */
    .items-table th:nth-child(6) { width: 14%; } /* Totale */
    .items-table th:nth-child(7) { width: 15%; } /* Totale(IVA) */

    /* Regola per l'allineamento e per evitare il ritorno a capo */
    .items-table th:not(:first-child),
    .items-table td:not(:first-child) {
        text-align: right;
        white-space: nowrap; /* Proprietà chiave per impedire il ritorno a capo */
    }

    .item-description strong { font-weight: 500; }

    

    /* NUOVA SEZIONE PER LE NOTE */
    .notes-section {
        margin-top: 20px;
        margin-bottom: 30px;
        font-size: 9pt;
        color: var(--font-color-light);
        page-break-inside: avoid !important;
    }
    .notes-section h4 {
        line-height: 1 !important;
        margin-bottom: 0px !important ;
        color: var(--font-color-dark);
        font-size: 9.5pt;
    }

    .notes-section .notes-content { /* <-- SELETTORE AGGIORNATO */
    white-space: pre-wrap;
    margin-top: 0 !important;
}
    /* FINE NUOVA SEZIONE */

    .invoice-summary { width: 45%; margin-left: auto; }
    .summary-table { width: 100%; border-collapse: collapse; }
    .summary-table th, .summary-table td { padding: 8px 10px; text-align: right; } /* RIDOTTO */
    .summary-table th { text-align: left; font-weight: 500; color: var(--font-color-light); }
    .summary-total { border-top: 2px solid var(--border-color); }
    .summary-total th, .summary-total td { font-size: 13pt; font-weight: 700; color: var(--primary-color); padding-top: 10px; } /* RIDOTTO */

    .invoice-footer {
    position: fixed;
    bottom: 0; /* Riporta il footer sul bordo inferiore della pagina */
    left: 1cm; /* Allinea il footer con il padding sinistro del body */
    right: 1cm; /* Allinea il footer con il padding destro del body */
    text-align: center;
    font-size: 8pt;
    color: var(--font-color-light);
    padding-bottom: 0.5cm; /* Aggiunge un po' d'aria dal fondo del foglio */
}
</style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="header-left">
                <img src="{{ $logo }}" alt="Logo" class="logo">
                <span class="company-name">{{config('company.name')}} - {{config('company.job')}}</span>
            </div>
            <div class="header-right">
                <h2 class="document-title">{{ $documentTitle }}</h2>
                <div class="document-details">
                    <p>Nr. {{ $invoice->invoice_number }}</p>
                    <p>Data: {{ $invoice->issue_date->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <div class="invoice-meta">
            <div class="meta-block">
                <p class="meta-heading">DA</p>
                <div class="meta-content">
                    <strong>{{ config('company.name') }} - {{config('company.job')}}</strong><br>
                    {{config('company.address')}}<br>
                    {{ config('company.city') }} {{ config('company.province') }}, {{config('company.zip_code')}}<br>
                    {{ config('company.email') }}  {{ config('company.number') }}
                </div>
            </div>
            <div class="meta-block client-info">
                <p class="meta-heading">{{ $invoice->type === 'invoice' ? 'Fatturato a' : 'Preventivo per' }}</p>
                <div class="meta-content">
                    <strong>{{ $invoice->client->company_name ?? $invoice->client->first_name . ' ' . $invoice->client->last_name }}</strong><br>
                    {{ $invoice->client->address }}<br>
                    {{ $invoice->client->city }} - {{ $invoice->client->country }}<br>
                    {{ $invoice->client->email }}
                </div>
                
                <p class="meta-heading" style="margin-top: 15px;">{{ $invoice->type === 'invoice' ? 'Data di Scadenza' : 'Valido fino al' }}</p>
                <p class="meta-content"><strong>{{ $invoice->due_date->format('d/m/Y') }}</strong></p>
            </div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>{{__('Description')}}</th>
                    <th>{{__('U/M')}}</th>
                    <th>{{__('Quantity')}}</th>
                    <th>{{__('Unit Price')}}</th>
                    <th>{{__('Vat')}}</th>
                    <th>{{__('Total')}}</th>
                    <th>{{__('Total(IVA)')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->invoiceItems as $item)
                <tr>
                    <td class="item-description"><strong>{{ $item->description }}</strong></td>
                    <td>{{$item->unit_of_measure}}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>€ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td>{{ number_format($item->vat_rate, 2, '.', '') }}%</td>
                    <td>€ {{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }}</td>
                    <td class="text-right">€ {{ number_format(($item->quantity * $item->unit_price) * (1 + $item->vat_rate / 100), 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($invoice->notes)
        <div class="notes-section">
            <h4>{{__('Notes')}}:</h4>
            <div class="notes-content">
                {{ $invoice->notes }}
            </div>
        </div>
        @endif

        <div class="invoice-summary">
            <table class="summary-table">
                <tr>
                    <th>Imponibile</th>
                    <td>€ {{ number_format($invoice->subtotal, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>IVA</th>
                    <td>€ {{ number_format($invoice->vat_amount, 2, ',', '.') }}</td>
                </tr>
                <tr class="summary-total">
                    <th>Totale</th>
                    <td>€ {{ number_format($invoice->total, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        {{-- <div class="invoice-footer">
            @if(config('company.site') != '')
                <span>{{config('company.site')}}</span> &bull;
            @endif
            <span>{{config('company.number')}}</span>
        </div> --}}
    </div>
</body>
</html>