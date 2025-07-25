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