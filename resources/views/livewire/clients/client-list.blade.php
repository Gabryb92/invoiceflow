<div class="mx-2">
    <x-slot name="header" class="">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Clients') }}
            </h2>
        </div>
    </x-slot>

    


    <div class="flex justify-between ml-auto py-6 px-4 sm:px-6 lg:px-8">

        <div class="w-full max-w-md">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" wire:model.live="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
                <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
            </div>
        </div>
        
        <div class="flex items-center flex-row space-x-4">    
            <select wire:model.live="showArchived" class="block  border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="0">Clienti Attivi</option>
                <option value="1">Clienti Archiviati</option>
            </select>
            
            <a href="{{ route('clienti.create') }}" class="flex flex-row items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Add Client') }}
            </a>
        </div>
    </div>

    <x-alert/>


    


    <div class="border border-gray-700 rounded-lg overflow-hidden">
        
        <table class="min-w-full divide-y divide-gray-700 text-gray-200">
        <thead class="font-bold bg-gray-800">
            <tr class="text-gray-100">
                <th class="text-[14px]  px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Name / Company Name') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Email') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Phone') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Fiscal Code') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse ($clients as $client)
                <tr wire:key="client-{{ $client->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('clienti.show',compact('client')) }}">{{ $client->company_name ?? $client->first_name . ' ' . $client->last_name }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $client->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $client->phone }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $client->fiscal_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                        <a href="{{ route('clienti.edit',compact('client')) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">Edit</a>
                        @if($showArchived)
                            <x-restore-button 
                                wire:click="restoreClient({{ $client->id }})"
                                wire:confirm="{{ __('Are you sure you want to restore this client?') }}">
                                {{ __('Restore') }}
                            </x-restore-button>
                            <x-danger-button 
                                wire:click="forceDelete({{ $client->id }})"
                                wire:confirm="{{ __('Are you sure you want to permanently delete this client?') }}">
                                {{ __('Delete') }}
                            </x-danger-button>
                            
                        @else
                        <x-danger-button 
                            wire:click="archiveClient({{ $client->id }})"
                            wire:confirm="{{ __('Are you sure you want to archive this client?') }}">
                            {{ __('Archive') }}
                        </x-danger-button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Nessun cliente trovato
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
   

    <div class="mt-4 py-2">
        {{ $clients->links() }}
    </div>
</div>