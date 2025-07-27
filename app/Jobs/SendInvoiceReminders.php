<?php

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\InvoiceReminderNotification;

class SendInvoiceReminders implements ShouldQueue
{
    use Queueable;

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
        
        $invoices = Invoice::query()
            ->with('client')
            ->whereIn('status', ['unpaid', 'partially_paid']) 
            ->whereDate('due_date', '=', now()->addDays(7))
            ->get();

        Log::info('Fatture trovate: ' . $invoices->count());
        
        foreach($invoices as $invoice){
            Log::info('Invio notifica per fattura N. ' . $invoice->invoice_number);
            $invoice->client->notify(new InvoiceReminderNotification($invoice));
        }


    }
}
