<div class="">
    <x-slot name="header" class="">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Clients') }}
            </h2>
        </div>
    </x-slot>

    


    <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center gap-4 py-6 px-4 sm:px-6 lg:px-8">

        <x-search-bar />
        
        <div class="flex items-center flex-row space-x-4">    
            <select wire:model.live="showArchived" class="block  border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="0">{{__('Clients active')}}</option>
                <option value="1">{{__('Clients archived')}}</option>
            </select>
            
            <a href="{{ route('clienti.create') }}" class="flex flex-row items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Add Client') }}
            </a>
        </div>
    </div>

    <x-alert/>


    


    <div class="border border-gray-700 rounded-lg overflow-x-auto sm:overflow-hidden m-2">
        
        <table class="min-w-full divide-y divide-gray-700 text-gray-200">
        <thead class="font-bold bg-gray-800">
            <tr class="text-gray-100">
                <th class="text-[14px]  px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Name / Company Name') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Email') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider hidden sm:table-cell">
                    {{ __('Phone') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider hidden sm:table-cell">
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
                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                        {{ $client->phone }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                        {{ $client->fiscal_code }}
                    </td>
                    @if ($client->company_name !== '[Cliente Anonimizzato]' && $client->first_name !== '[Dato Rimosso]')
                        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                            <a href="{{ route('clienti.edit',compact('client')) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{__('Edit')}}</a>
                            @if($showArchived)
                                <x-restore-button 
                                    wire:click="restoreClient({{ $client->id }})"
                                    wire:confirm="{{ __('Are you sure you want to restore this client?') }}">
                                    {{ __('Restore') }}
                                </x-restore-button>
                                <x-danger-button 
                                    wire:click="anonymizeClient({{ $client->id }})"
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
                    @else
                        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                            <span class="text-gray-500">--</span>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        {{ __('No Client Found') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    </div>
   
    <div class="mt-4 py-2 mx-2">
        {{ $clients->links() }}
    </div>
    
</div>