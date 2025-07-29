<?php

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\InvoiceReminderNotification;

class SendInvoiceReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Esecuzione del Job SendInvoiceReminders...');

        Log::info('Oggi è: ' . now()->toDateString());
        Log::info('Data limite: ' . now()->addDays(7)->toDateString());
        
        $invoices = Invoice::query()
            ->with('client')
            ->whereIn('status', ['unpaid', 'partially_paid']) 
            ->whereDate('due_date', '<=', now()->addDays(7))
            ->get();

        Log::info('Fatture trovate: ' . $invoices->count());
        
        foreach($invoices as $invoice){
            $delay = now()->addSeconds(rand(1, 30)); // delay casuale tra 1 e 30 secondi
            Log::info("Dispatch job per fattura {$invoice->invoice_number} con delay di {$delay->diffInSeconds()} secondi.");
            //$invoice->client->notify(new InvoiceReminderNotification($invoice));
            SendInvoiceReminderEmail::dispatch($invoice)->delay($delay);
        }


    }
}
