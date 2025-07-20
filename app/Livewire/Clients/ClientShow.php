<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;

class ClientShow extends Component
{
     // Questo è il metodo che ti mancava
    public function mount(Client $client)
    {
        $this->client = $client;
    }
    public $client;
    public function render()
    {
        return view('livewire.clients.client-show')->layout('layouts.app');
    }
}
