<?php

namespace App\Livewire\Payments;

use Exception;
use App\Models\Invoice;
use App\Models\Payment;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentForm extends Component
{
    public $showModal = false;

    public $invoice_id;

    public $amount_paid = 0.00;
    public $payment_date;

    public $payment_method = "";

    public $notes = "";

    public function rules(){
        return [
            "amount_paid" => "required|numeric",
            "payment_date" => "required|date",
            "payment_method" => "string",
            "notes" => "string",
        ];
    }

    #[On('showPaymentModal')]
    public function openModal(int $invoice_id_from_show){
        $this->invoice_id = $invoice_id_from_show;
        $this->showModal = true;
    }

    public function closeModal(){
        $this->showModal = false;
        $this->reset();
    }

    public function savePayment(){
        $dataValidated = $this->validate();

        try{

            $dataValidated['invoice_id'] =  $this->invoice_id;
            DB::transaction(function() use ($dataValidated) {
                Payment::create($dataValidated);
                $invoice = Invoice::find($this->invoice_id);
                $totalInvoice = $invoice->total;
                $totalPaid = $invoice->payments()->sum('amount_paid');
                if($totalPaid>= $totalInvoice){
                    //Significa che è stata saldata
                    $invoice->update([
                        "status" => "paid"
                    ]);
                } else if ($totalPaid > 0) {
                    $invoice->update([
                        "status" => "partially_paid"
                    ]);
                }
                
            });

            //Inviare l'evento alla pagina genitore per dirle di aggiornarsi:
            $this->dispatch('paymentSaved');

            //Mostrare il messaggio:
            session()->flash('message', "Payment correctly confirmed");

            //Chiudiamo la modale
            $this->closeModal();
            
        } catch (Exception $e) {
            Log::error('Errore durante il salvataggio del pagamento della fattura', [
                'message' => $e->getMessage(),
                'payment_data' => $this->all(),
            ]);
        }
    }
    public function render()
    {
        return view('livewire.payments.payment-form');
    }
}
