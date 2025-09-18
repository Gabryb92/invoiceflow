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

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* * MODIFICA #1: RISOLUZIONE PADDING ESTERNO 
         * Rimuoviamo @page e applichiamo il padding direttamente al body
         * per massima compatibilità con i generatori PDF.
         */
        body {
            font-family: var(--font-family);
            font-size: 10pt;
            color: var(--font-color-dark);
            background-color: #fff;
            line-height: 1.6;
            padding: 1.5cm; /* <-- ECCO LA SOLUZIONE PER I BORDI */
        }
        
        .invoice-container { width: 100%; margin: 0 auto; }
        .invoice-header { display: table; width: 100%; margin-bottom: 40px; }
        .header-left, .header-right { display: table-cell; vertical-align: middle; }
        .header-left { width: 60%; }
        .header-right { width: 40%; text-align: right; }
        .logo { width: 50px; height: auto; margin-right: 15px; vertical-align: middle; }
        .company-name { font-size: 16pt; font-weight: 700; display: inline-block; vertical-align: middle; }
        .document-title { font-size: 20pt; font-weight: 700; color: var(--primary-color); margin: 0; }
        .document-details p { margin: 2px 0 0 0; font-size: 10pt; color: var(--font-color-light); }

        .invoice-meta {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            padding: 20px 0;
        }

        .meta-block {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }

        .meta-heading {
            font-size: 8pt;
            font-weight: 500;
            color: var(--font-color-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .meta-content {
            font-size: 10pt;
            line-height: 1.5;
        }
        
        .meta-content strong { font-weight: 500; }

        /* * MODIFICA #2: ALLINEAMENTO BLOCCO CLIENTE
         * Aggiungiamo text-align: right per allineare tutto il contenuto
         * del blocco destro sulla destra.
         */
        .meta-block.client-info {
            padding-left: 30px;
            text-align: right; /* <-- ECCO LA SOLUZIONE PER L'ALLINEAMENTO */
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            text-align: left;
            padding: 12px 10px;
            background-color: var(--background-light);
            color: var(--font-color-light);
            font-size: 9pt;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }

        /*Prove per spazio automatico voci preventivo/fattura*/

        .items-table tr {
            page-break-inside: avoid;
        }

        
        .invoice-summary {
            page-break-inside: avoid; 
            page-break-before: auto;  
        }

        /*Fine prove*/
            
        .items-table th:nth-child(2), .items-table td:nth-child(2),
        .items-table th:nth-child(3), .items-table td:nth-child(3),
        .items-table th:nth-child(4), .items-table td:nth-child(4),
        .items-table th:nth-child(5), .items-table td:nth-child(5) {
            text-align: right;
        }

        .item-description strong { font-weight: 500; }
        .invoice-summary { width: 45%; margin-left: auto; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table th, .summary-table td { padding: 10px; text-align: right; }
        .summary-table th { text-align: left; font-weight: 500; color: var(--font-color-light); }
        .summary-total { border-top: 2px solid var(--border-color); }
        .summary-total th, .summary-total td { font-size: 14pt; font-weight: 700; color: var(--primary-color); padding-top: 15px; }

        /* Posizioniamo il footer in fondo alla pagina in modo più robusto */
        .invoice-footer {
            position: fixed;
            bottom: -50px; /* Adatta questo valore se necessario */
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8pt;
            color: var(--font-color-light);
        }

    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="header-left">
                <img src="{{ $logo }}" alt="Logo" class="logo">
                <span class="company-name">Gabriele Bonazza - Developer</span>
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
                    <strong>Gabriele Bonazza - Developer</strong><br>
                    Viale Giacomo Leopardi 100<br>
                    Lido degli Estensi (FE), 44029<br>
                    gabriele.bonazza92@gmail.com
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
                    <th>Descrizione</th>
                    <th>Quantità</th>
                    <th>Prezzo Unit.</th>
                    <th>IVA</th>
                    <th>Totale</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->invoiceItems as $item)
                <tr>
                    <td class="item-description"><strong>{{ $item->description }}</strong></td>
                    <td>{{ $item->quantity }}</td>
                    <td>€ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td>{{ number_format($item->vat_rate, 2, '.', '') }}%</td>
                    <td>€ {{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

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

        <div class="invoice-footer">
            <span>gabrielebonazzadeveloper.com</span> &bull;
            <span>+39 351 852 3204</span>
        </div>
    </div>
</body>
</html>