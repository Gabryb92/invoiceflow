<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fattura {{ $invoice->invoice_number }}</title>
    <style>
        /* Import font - alcuni generatori PDF potrebbero non supportarlo */
        @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap");

        :root {
            --font-color: #000000;
            --highlight-color: #60e48e;
            --header-bg-color: #bff1b8;
            --footer-bg-color: #bfc0c3;
            --table-row-separator-color: #bfc0c3;
        }

        /* Regole specifiche per la pagina PDF */
        @page {
            size: A4;
            margin: 2cm 1cm 3cm 1cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            color: var(--font-color);
            font-family: "Montserrat", Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* Header section */
        .invoice-header {
            background-color: var(--header-bg-color);
            padding: 20px;
            margin-bottom: 20px;
        }

        .header-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .header-left,
        .header-right {
            display: table-cell;
            vertical-align: top;
        }

        .header-left {
            width: 65%;
        }

        .header-right {
            width: 35%;
            text-align: right;
        }

        .logo-section {
            margin-bottom: 20px;
        }

        .logo-svg {
            display: inline-block;
            width: 30px;
            height: 30px;
            vertical-align: middle;
            margin-right: 10px;
        }

        .company-name {
            display: inline-block;
            vertical-align: middle;
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
        }

        .invoice-details h2 {
            font-size: 14pt;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .invoice-details p {
            margin: 0;
        }

        .divider {
            height: 2px;
            background-color: var(--highlight-color);
            margin: 20px 0;
            border: none;
        }

        .client-info h3 {
            color: var(--highlight-color);
            text-transform: uppercase;
            margin: 0 0 10px 0;
            font-size: 10pt;
        }

        .client-info p {
            margin: 0 0 15px 0;
            line-height: 1.5;
        }

        .amount-info h3 {
            color: var(--highlight-color);
            text-transform: uppercase;
            margin: 0 0 10px 0;
            font-size: 10pt;
        }

        .amount-info p {
            margin: 0 0 20px 0;
        }

        /* Table styles */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .invoice-table th {
            background-color: transparent;
            color: var(--highlight-color);
            text-align: left;
            padding: 10px 5px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }

        .invoice-table th:last-child,
        .invoice-table td:last-child {
            text-align: right;
        }

        .invoice-table td {
            padding: 8px 5px;
            border-bottom: 1px solid #eee;
        }

        /* Summary table */
        .summary-table {
            width: 35%;
            margin-left: auto;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .summary-table th {
            background-color: transparent;
            text-align: left;
            padding: 8px 10px;
            font-weight: normal;
        }

        .summary-table td {
            text-align: right;
            padding: 8px 10px;
        }

        .summary-table .total-row {
            background-color: var(--highlight-color);
            font-weight: bold;
        }

        .summary-table .total-row th,
        .summary-table .total-row td {
            padding: 10px;
        }

        /* Terms section */
        .terms-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid var(--highlight-color);
        }

        .terms-content {
            display: table;
            width: 100%;
        }

        .terms-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            padding-right: 20px;
        }

        .terms-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }

        .terms-left h4,
        .terms-right h4 {
            margin: 0 0 10px 0;
            font-weight: bold;
        }

        .terms-left p {
            margin: 0;
            line-height: 1.5;
        }

        .terms-right ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .terms-right li {
            margin-bottom: 5px;
        }

        .invoice-wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Footer - Fixed at bottom */
        

        .invoice-footer {
            position: fixed;
            bottom: -80;
            left: 0;
            right: 0;
            text-align: center; /* Cruciale per centrare il blocco interno */
            font-size: 8pt;
            padding-bottom: 20px; /* Aggiungiamo un po' di spazio dal fondo */
        }

        .footer-content {
            display: table;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background-color: var(--footer-bg-color);
            padding: 15px 20px;
            box-sizing: border-box;
            
        }

        .footer-item {
            display: table-cell;
            text-align: center;
            padding: 0 10px;
        }

        .footer-item a {
            font-weight: bold;
        }

        /* Aggiungi padding bottom al body per evitare sovrapposizioni */
        body {
            margin: 0;
            padding: 0 0 80px 0;
            color: var(--font-color);
            font-family: "Montserrat", Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }

        /* Utility classes */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        /* Print specific adjustments */
        @media print {
            .invoice-header {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .invoice-footer {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            .summary-table .total-row {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">

        
        <div class="invoice-header">
            <div class="header-row">
                <div class="header-left">
                    <div class="logo-section" style="display: flex; align-items: center;">
    
                        <img src="{{ $logo }}" style="width: 60px; height: auto; margin-right: 15px;" alt="Logo">
                        
                        <h1 class="company-name">Gabriele Bonazza - Developer</h1>

                    </div>
                </div>
                <div class="header-right">
                    <div class="invoice-details">
                        <h2>Invoice {{ $invoice->invoice_number }}</h2>
                        <p>{{ $invoice->issue_date->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>

            <hr class="divider">

            <div class="header-row">
                <div class="header-left">
                    <div class="client-info">
                        <h3>Invoice to</h3>
                        <p>
                            <strong>{{ $invoice->client->company_name ?? $invoice->client->first_name . ' ' . $invoice->client->last_name }}</strong><br>
                            {{ $invoice->client->address }}<br>
                            {{ $invoice->client->city }} - {{ $invoice->client->country }}<br>
                            <a href="mailto:{{ $invoice->client->email }}">{{ $invoice->client->email }}</a><br>
                            {{ $invoice->client->phone }}
                        </p>
                    </div>
                </div>
                <div class="header-right">
                    <div class="amount-info">
                        <h3>Due Date</h3>
                        <p><strong>{{ $invoice->due_date->format('Y-m-d') }}</strong></p>
                        
                        <h3>Amount</h3>
                        <p><strong>&euro; {{ number_format($invoice->total, 2, ',', '.') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Descrizione</th>
                    <th>Quantità</th>
                    <th>Prezzo Unitario</th>
                    <th>Iva</th>
                    <th>Total(No IVA)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->invoiceItems as $item)
                <tr>
                    <td><strong>{{ $item->description }}</strong></td>
                    <td>{{ $item->quantity }}</td>
                    <td>&euro; {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                    <td>{{ number_format($item->vat_rate, 2, ',', '.') }}%</td>
                    <td>&euro; {{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary table -->
        <table class="summary-table">
            <tr>
                <th>Imponibile</th>
                <td>&euro; {{ number_format($invoice->subtotal, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>IVA</th>
                <td>&euro; {{ number_format($invoice->vat_amount, 2, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <th>Totale</th>
                <td>&euro; {{ number_format($invoice->total, 2, ',', '.') }}</td>
            </tr>
        </table>

        <!-- Terms and conditions -->
        <div class="terms-section">
            <div class="terms-content">
                <div class="terms-left">
                    <h4>Termini &amp; Condizioni</h4>
                    <p>Si prega di effettuare il pagamento entro 30 giorni dall'emissione della fattura.</p>
                </div>
                <div class="terms-right">
                    <h4>Opzioni di pagamento</h4>
                    <ul>
                        <li>Paypal</li>
                        <li>Stripe</li>
                        <li>Bonifico</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-content">
                <div class="footer-item">
                    <a href="https://gabrielebonazzadeveloper.com">gabrielebonazzadeveloper.com</a>
                </div>
                <div class="footer-item">
                    <a href="mailto:gabriele.bonazza92@gmail.com">gabriele.bonazza92@gmail.com</a>
                </div>
                <div class="footer-item">
                    <a href="tel:+393518523204">3518523204</a>
                </div>
                <div class="footer-item">
                    Lido degli Estensi (FE), Viale Giacomo Leopardi 100, 44029
                </div>
            </div>
        </div>

    </div>
</body>
</html>