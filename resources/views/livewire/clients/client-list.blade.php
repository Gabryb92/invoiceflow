<div>
    <x-slot name="header" class="">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Clients') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl flex justify-end ml-auto py-6 px-4 sm:px-6 lg:px-8">
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
                        {{ $client->company_name ?? $client->first_name . ' ' . $client->last_name }}
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
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="#" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <a href="#" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
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
   

    <div class="mt-4">
        {{ $clients->links() }}
    </div>
</div>