<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    private function isAnonymized(Client $client): bool
    {
        return $client->company_name === '[Cliente Anonimizzato]' || $client->first_name === '[Dato Rimosso]';
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Client $client): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Client $client): bool
    {
        // Permetti l'aggiornamento solo se il cliente NON è anonimizzato.
        return !$this->isAnonymized($client);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Client $client): bool
    {
        // Permetti la cancellazione/archiviazione solo se il cliente NON è anonimizzato.
        return !$this->isAnonymized($client);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Client $client): bool
    {
        // Permetti il ripristino solo se il cliente NON è anonimizzato.
        return !$this->isAnonymized($client);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Client $client): bool
    {
        return false;
    }
}
