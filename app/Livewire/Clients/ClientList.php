<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    
    public $showArchived = false;

    public $search = "";


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

    public function anonymizeClient(int $clientId){
        $client = Client::withTrashed()->findOrFail($clientId);
        // I tuoi dati di anonimizzazione sono perfetti
        $client->update([
            "first_name"   => "[Dato Rimosso]",
            "last_name"    => "",
            "company_name" => "[Cliente Anonimizzato]",
            "email"        => $client->id . '_' . time() . '@deleted.user', // Reso ancora più unico
            "phone"        => "",
            "address"      => "",
            "city"         => "",
            "zip_code"     => "",
            "province"     => "",
            "country"      => "",
            "vat_number"   => null, // Meglio null per rispettare i vincoli UNIQUE
            "fiscal_code"  => null, // Meglio null per rispettare i vincoli UNIQUE
            "notes"        => "Dati cliente rimossi su richiesta.",
        ]);

        if(!$client->trashed()){
            $client->delete();
        }

        session()->flash('message', 'Client permanently deleted successfully.');

    }

    public function restoreClient($clientId){
        $client = Client::withTrashed()->findOrFail($clientId);
        $client->restore();
        session()->flash('message', 'Client restored successfully.');
    }
    
    public function render()
    {
        $query = Client::query();
        // Converti il valore a booleano in modo sicuro
        $isArchived = (bool) $this->showArchived;
        if($isArchived){
            $query->onlyTrashed();
        }


        $query->when($this->search, function ($q) {
            $q->where(function ($subQuery) {
                $searchItem = '%' . $this->search . '%';
                $subQuery->where('first_name', 'like', $searchItem)
                    ->orWhere('last_name', 'like', $searchItem)
                    ->orWhere('company_name', 'like', $searchItem)
                    ->orWhere('email', 'like', $searchItem);
            });
        });

        
        $clients = $query->latest()->paginate(10);

        return view('livewire.clients.client-list', compact('clients'))->layout('layouts.app');
    }
}