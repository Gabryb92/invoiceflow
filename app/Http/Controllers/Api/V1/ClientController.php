<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Usiamo ClientResource::collection per formattare una lista
        return ClientResource::collection(Client::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required_without:company_name|nullable|string|max:255',
            'last_name'      => 'required_without:company_name|nullable|string|max:255',
            'company_name'   => 'required_without_all:first_name,last_name|nullable|string|max:255',
            
            
            'email'          => 'required|email|unique:clients,email',
            'vat_number'     => 'nullable|string|unique:clients,vat_number',
            'fiscal_code'    => 'nullable|string|unique:clients,fiscal_code',

            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:100',
            'province'       => 'nullable|string|max:2',
            'zip_code'       => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
        ]);

        $client = Client::create($validated);

        return (new ClientResource($client)->response()->setStatusCode(201));

    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load('invoices');
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'first_name'     => 'required_without:company_name|nullable|string|max:255',
            'last_name'      => 'required_without:company_name|nullable|string|max:255',
            'company_name'   => 'required_without_all:first_name,last_name|nullable|string|max:255',
            
            
            'email'          => 'required|email|unique:clients,email,'. $client->id,
            'vat_number'     => 'nullable|string|unique:clients,vat_number,'. $client->id,
            'fiscal_code'    => 'nullable|string|unique:clients,fiscal_code,'. $client->id,

            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:100',
            'province'       => 'nullable|string|max:2',
            'zip_code'       => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
        ]);

        $client->update($validated);

        return new ClientResource($client);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);
        $client->delete();
        return response()->noContent();
    }

    public function anonymize(Client $client){
        $this->authorize('delete', $client);

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
                "is_anonymized" => true,
                "notes"        => "Dati cliente rimossi su richiesta.",
            ]);

        if(!$client->trashed()){
                $client->delete();
            }
        return response()->json(['message'=> "Client correctly deleted"]);
    }

    public function restore(Client $client){
        $this->authorize('restore', $client);
        $client->restore();

        return response()->json(["message" => "Client correctly restored"]);
    }

    
}
