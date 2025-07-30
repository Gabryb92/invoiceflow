# InvoiceFlow - Gestionale

Un'applicazione web completa per la gestione di clienti, prodotti e fatture, costruita con lo stack TALL (Tailwind, Alpine.js, Laravel, Livewire).

<p align="center">
  <img src="public/img/invoiceflow400x400.png" alt="logo of invoice flow" title="Invoice Flow" />
</p>

## 🚀 Informazioni sul Progetto

InvoiceFlow è un'applicazione web su misura progettata per semplificare la gestione amministrativa di una piccola azienda o un freelance. Permette di gestire un catalogo di prodotti/servizi, un'anagrafica clienti e l'intero ciclo di vita delle fatture, dalla creazione all'incasso, con funzionalità automatiche per risparmiare tempo.

Il progetto è stato sviluppato seguendo le best practice moderne, con un'architettura reattiva basata su componenti e un'attenzione particolare alla sicurezza e alla robustezza del codice.

#### 🛠️ Costruito Con

-   [Laravel](https://laravel.com/): Il framework PHP per il backend, che gestisce la logica, le rotte, il database e la sicurezza.

-   [Livewire](https://laravel-livewire.com/): Per creare un'interfaccia dinamica e reattiva senza dover scrivere JavaScript complesso.

-   [Tailwind CSS](https://tailwindcss.com/): Per uno stile moderno e un'interfaccia utente completamente personalizzata e responsiva.

-   [Alpine.js](https://alpinejs.dev/): Per piccole interattività nel frontend, come la chiusura automatica degli alert e le animazioni delle modali.

-   [Chart.js](https://www.chartjs.org/): Per visualizzare i grafici nella dashboard.

-   [MySQL](https://www.mysql.com/it/): Come database relazionale per la gestione dei dati.

-   [DomPDF](https://github.com/barryvdh/laravel-dompdf): Per la generazione dinamica delle fatture in formato PDF.

---

#### ✨ Funzionalità Implementate

-   Dashboard Riepilogativa: Una visione d'insieme con indicatori chiave (KPI) come il fatturato annuale, gli importi da incassare e un grafico delle entrate mensili.

-   Gestione Clienti (CRUD):

    -   Creazione, modifica e visualizzazione dei clienti (sia persone fisiche che aziende).

    -   Archiviazione (Soft Delete) e ripristino dei clienti.

    -   Anonimizzazione per la conformità GDPR, che rimuove i dati personali mantenendo l'integrità dei dati fiscali.

    -   Policy di sicurezza per impedire modifiche ai clienti anonimizzati.

-   Gestione Prodotti (CRUD): Un catalogo completo per i servizi e i prodotti, con possibilità di archiviazione.

-   Gestione Fatture (CRUD):

    -   Creazione di fatture dinamiche con aggiunta di voci dal catalogo, sconti e spese di spedizione.

    -   Calcoli dei totali (imponibile, IVA, totale) in tempo reale.

    -   Modifica e cancellazione delle fatture.

    -   Pagine di dettaglio per ogni cliente e ogni fattura.

-   Gestione Pagamenti: Un sistema a modale per registrare pagamenti parziali o totali, con aggiornamento automatico dello stato della fattura.

-   Ricerca Avanzata: Barre di ricerca reattive su clienti, prodotti e fatture, con ricerca anche all'interno delle relazioni (es. cercare una fattura tramite il nome del cliente).

-   Email Automatiche: Un Job schedulato che viene eseguito giornalmente per inviare promemoria via email per le fatture in scadenza.

-   Generazione PDF: Possibilità di scaricare una versione PDF professionale di ogni fattura.

-   Traduzioni: Interfaccia e messaggi di validazione completamente tradotti in italiano.

---

#### ⚙️ Installazione

Per eseguire il progetto in locale

1.  Clona la repository

    ```BASH
    git clone https://github.com/Gabryb92/invoiceflow.git
    ```

2.  Entra nella cartella progetto

    ```BASH
    cd invoiceflow
    ```

3.  Installazione dipendenze PHP

    ```BASH
    composer install
    ```

4.  Installazione npm:

    ```BASH
    npm install
    ```

5.  Copia del file `.env`

    ```BASH
    cp .env.example .env
    ```

6.  Genera la chiave dell'applicazione

    ```BASH
    php artisan key:generate
    ```

7.  Configura i dettagli del tuo database nel file `.env`

    ```ENV
    DB_CONNECTION=mysql(sqlite ecc)
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nomedb()
    DB_USERNAME=userdb
    DB_PASSWORD=userpassword
    ```

8.  Esegui le migrazioni:

    ```BASH
    php artisan migrate
    ```

    Il progetto prevede dei dati fake per i test, basta lanciare il seeder:

    ```BASH
    php artisan migrate --seed
    ```
