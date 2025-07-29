<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InvoiceReminderNotification extends Notification
{
    use Queueable;

    public Invoice $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $clientName = $this->invoice->client->company_name ?? $this->invoice->client->first_name;
        $dueDate = $this->invoice->due_date->format('d/m/Y');
        $url = route('fatture.show',$this->invoice);
        return (new MailMessage)
            ->subject('Promemoria Scadenza: Fattura N.' . $this->invoice->invoice_number)
            ->greeting('Gentile ' . $clientName . ',')
            ->line('Ti inviamo un promemoria per la fattura numero ' . $this->invoice->invoice_number . ' di €' . number_format($this->invoice->total, 2, ',', '.'))
            ->line('La data di scadenza per il pagamento è il ' . $dueDate . '.')
            ->action('Visualizza Fattura', $url)
            ->line('Grazie per la tua collaborazione.');
            
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
