<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class ClientList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    
    public $showArchived = false;


    public function archiveClient($clientId){
        $client = Client::withTrashed()->findOrFail($clientId);
        $client->delete();
        session()->flash('message', 'Client archived successfully.');
    }

    public function forceDelete($clientId){
        $client = Client::withTrashed()->findOrFail($clientId);
        $client->forceDelete();
        session()->flash('message', 'Client permanently deleted successfully.');
    }

    public function restoreClient($clientId){
        $client = Client::withTrashed()->findOrFail($clientId);
        $client->restore();
        session()->flash('message', 'Client restored successfully.');
    }
    
    public function render()
    {
        // Converti il valore a booleano in modo sicuro
        $isArchived = (bool) $this->showArchived;
        
        $clients = $isArchived
            ? Client::onlyTrashed()->latest()->paginate(10)
            : Client::latest()->paginate(10);
        
        // info('Dati Clienti Passati alla Vista:', $clients->toArray());
        // info('Debug valori:', [
        //     'showArchived_originale' => $this->showArchived,
        //     'showArchived_tipo' => gettype($this->showArchived),
        //     'isArchived_convertito' => $isArchived
        // ]);

        return view('livewire.clients.client-list', compact('clients'))->layout('layouts.app');
    }
}