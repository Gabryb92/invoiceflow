<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Notifications\InvoiceReminderNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendInvoiceReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Invoice $invoice) {}

    public function handle(): void
    {
        try {
            $client = $this->invoice->client;

            if (!$client || !$client->email) {
                Log::warning("Fattura {$this->invoice->invoice_number}: client mancante o senza email.");
                return;
            }

            Log::info("Invio notifica per fattura {$this->invoice->invoice_number} a {$client->email}");

            $client->notify(new InvoiceReminderNotification($this->invoice));
        } catch (\Throwable $e) {
            Log::error("Errore invio email per fattura {$this->invoice->invoice_number}: " . $e->getMessage());
        }
    }
}
