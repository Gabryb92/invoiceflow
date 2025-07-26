<?php

namespace App\Livewire\Clients;

use Exception;
use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ClientList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    
    public $showArchived = false;

    public $search = "";


    public function archiveClient($clientId){
        try{

            $client = Client::findOrFail($clientId);
            $client->delete();
            session()->flash('message', 'Client archived successfully.');
        } catch (Exception $e) {
            session()->flash('error', "An error occurred while archiving, please try again later.");
            Log::error($e->getMessage());
        }
    }

    public function forceDelete($clientId){
        try{

            $client = Client::withTrashed()->findOrFail($clientId);
            $client->forceDelete();
            session()->flash('message', 'Client permanently deleted successfully.');
        } catch (Exception $e){
            session()->flash('error', "An error occurred during forced deletion. Please try again later.");
            Log::error($e->getMessage());
        }
    }

    public function anonymizeClient(int $clientId){
        try{

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
        } catch(Exception $e) {
            session()->flash('error', "An error occurred during forced deletion. Please try again later.");
            Log::error($e->getMessage());
        }


    }

    public function restoreClient($clientId){
        try{

            $client = Client::withTrashed()->findOrFail($clientId);
            $client->restore();
            session()->flash('message', 'Client restored successfully.');
        } catch(Exception $e) {
            session()->flash('error', "An error occurred during the restore. Please try again later.");
            Log::error($e->getMessage());
        }
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