<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ClientForm extends Component
{

    public ?Client $client;
    public $first_name = '';
    public $last_name = '';
    public $company_name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $city = '';
    public $province = '';
    public $zip_code = '';
    public $country = '';
    public $vat_number = '';
    public $fiscal_code = '';
    public $notes = '';


    //Il form servirà sia per la creazione che per l'aggiornamento di un cliente
    public function mount(Client $client) {
        $this->client = $client;

        if($this->client->exists){
            $this->first_name = $this->client->first_name;
            $this->last_name = $this->client->last_name;
            $this->company_name = $this->client->company_name;
            $this->email = $this->client->email;
            $this->phone = $this->client->phone;
            $this->address = $this->client->address;
            $this->city = $this->client->city;
            $this->province = $this->client->province;
            $this->zip_code = $this->client->zip_code;
            $this->country = $this->client->country;
            $this->vat_number = $this->client->vat_number;
            $this->fiscal_code = $this->client->fiscal_code;
            $this->notes = $this->client->notes;
        }
    }


    protected function rules(){

        $clientId = $this->client?->id;

        return [
            'first_name'     => 'required_without:company_name|nullable|string|max:255',
            'last_name'      => 'required_without:company_name|nullable|string|max:255',
            'company_name'   => 'required_without_all:first_name,last_name|nullable|string|max:255',
            
            
            'email'          => 'required|email|unique:clients,email,' . $clientId,
            'vat_number'     => 'nullable|string|unique:clients,vat_number,'. $clientId,
            'fiscal_code'    => 'nullable|string|unique:clients,fiscal_code,'. $clientId,

            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:100',
            'province'       => 'nullable|string|max:2',
            'zip_code'       => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
        ];
    }

    
    public function save(){
        //dd($this->all());
        $validatedData = $this->validate();

        if($this->client->exists){
            //Se il cliente esiste, aggiorniamo i dati
            $this->client->update($validatedData);
            session()->flash('message', 'Client updated successfully.');
        } else {
            //Altrimenti, creiamo un nuovo cliente
            Client::create($validatedData);
            session()->flash('message', 'Client created successfully.');

            $this->reset();
            
        }
        
        
        
    }

    public function render()
    {
        $provinces = [
        'AG' => 'Agrigento', 'AL' => 'Alessandria', 'AN' => 'Ancona', 'AO' => 'Aosta', 'AR' => 'Arezzo', 'AP' => 'Ascoli Piceno', 'AT' => 'Asti', 'AV' => 'Avellino', 'BA' => 'Bari', 'BT' => 'Barletta-Andria-Trani', 'BL' => 'Belluno', 'BN' => 'Benevento', 'BG' => 'Bergamo', 'BI' => 'Biella', 'BO' => 'Bologna', 'BZ' => 'Bolzano', 'BS' => 'Brescia', 'BR' => 'Brindisi', 'CA' => 'Cagliari', 'CL' => 'Caltanissetta', 'CB' => 'Campobasso', 'CE' => 'Caserta', 'CT' => 'Catania', 'CZ' => 'Catanzaro', 'CH' => 'Chieti', 'CO' => 'Como', 'CS' => 'Cosenza', 'CR' => 'Cremona', 'KR' => 'Crotone', 'CN' => 'Cuneo', 'EN' => 'Enna', 'FM' => 'Fermo', 'FE' => 'Ferrara', 'FI' => 'Firenze', 'FG' => 'Foggia', 'FC' => 'Forlì-Cesena', 'FR' => 'Frosinone', 'GE' => 'Genova', 'GO' => 'Gorizia', 'GR' => 'Grosseto', 'IM' => 'Imperia', 'IS' => 'Isernia', 'SP' => 'La Spezia', 'AQ' => 'L\'Aquila', 'LT' => 'Latina', 'LE' => 'Lecce', 'LC' => 'Lecco', 'LI' => 'Livorno', 'LO' => 'Lodi', 'LU' => 'Lucca', 'MC' => 'Macerata', 'MN' => 'Mantova', 'MS' => 'Massa-Carrara', 'MT' => 'Matera', 'ME' => 'Messina', 'MI' => 'Milano', 'MO' => 'Modena', 'MB' => 'Monza e della Brianza', 'NA' => 'Napoli', 'NO' => 'Novara', 'NU' => 'Nuoro', 'OR' => 'Oristano', 'PD' => 'Padova', 'PA' => 'Palermo', 'PR' => 'Parma', 'PV' => 'Pavia', 'PG' => 'Perugia', 'PU' => 'Pesaro e Urbino', 'PE' => 'Pescara', 'PC' => 'Piacenza', 'PI' => 'Pisa', 'PT' => 'Pistoia', 'PN' => 'Pordenone', 'PZ' => 'Potenza', 'PO' => 'Prato', 'RG' => 'Ragusa', 'RA' => 'Ravenna', 'RC' => 'Reggio Calabria', 'RE' => 'Reggio Emilia', 'RI' => 'Rieti', 'RN' => 'Rimini', 'RM' => 'Roma', 'RO' => 'Rovigo', 'SA' => 'Salerno', 'SS' => 'Sassari', 'SV' => 'Savona', 'SI' => 'Siena', 'SR' => 'Siracusa', 'SO' => 'Sondrio', 'SU' => 'Sud Sardegna', 'TA' => 'Taranto', 'TE' => 'Teramo', 'TR' => 'Terni', 'TO' => 'Torino', 'TP' => 'Trapani', 'TN' => 'Trento', 'TV' => 'Treviso', 'TS' => 'Trieste', 'UD' => 'Udine', 'VA' => 'Varese', 'VE' => 'Venezia', 'VB' => 'Verbano-Cusio-Ossola', 'VC' => 'Vercelli', 'VR' => 'Verona', 'VV' => 'Vibo Valentia', 'VI' => 'Vicenza', 'VT' => 'Viterbo'
    ];
        return view('livewire.clients.client-form',compact('provinces'))->layout('layouts.app');
    }
}
