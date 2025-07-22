<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;

class ClientShow extends Component
{
    public $client;

    public $total_of_all_invoices = 0;
    public $total_paid = 0;
    public $total_not_paid = 0;
    
    public function mount(Client $client)
    {
        $this->client = $client;

        $this->client->load('invoices');
    }

    public function calculateTotals(){
        $invoices = $this->client->invoices;

        $this->total_of_all_invoices = $invoices->where('status', '!=', 'cancelled')->sum('total');

        $this->total_paid = $invoices->where('status', 'paid')->sum('total');
        $this->total_not_paid = $this->total_of_all_invoices - $this->total_paid;
    }
    public function render()
    {

        $this->calculateTotals();
        
        return view('livewire.clients.client-show',)->layout('layouts.app');
    }
}
