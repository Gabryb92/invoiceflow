<div>
    {{-- <x-slot name="header">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Invoices') }}
            </h2>
        </div>
    </x-slot> --}}

    <x-slot name="header">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                @if($filterType === 'invoice')
                    {{ __('Invoices') }}
                @else
                    {{ __('Quote') }}
                @endif
            </h2>
        </div>
    </x-slot>

    

    <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center gap-4 py-6 px-4 sm:px-6 lg:px-8">

        <div class="w-full max-w-md">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search" wire:model.live="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="">
            </div>
        </div>
        
        <div class="flex justify-end sm:items-center flex-row space-x-4">
            
            <select wire:model.live="filterType" class="block  border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="invoice">{{__('Fatture')}}</option>
                <option value="quote">{{__('Preventivi')}}</option>
            </select>

            <a href="{{$filterType === 'invoice' ? route('fatture.create') : route('preventivi.create') }}" class="grow flex flex-row justify-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150">
                {{ $filterType === 'invoice' ? __('Add Invoice') : __('Add Quote') }}
            </a>
        </div>
    </div>


    <x-alert />
    

    <div class="border border-gray-700 rounded-lg overflow-x-auto sm:overflow-hidden m-2">
        
        <table class="min-w-full divide-y divide-gray-700 text-gray-200">
        <thead class="font-bold bg-gray-800">
            <tr class="text-gray-100">
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{$filterType === 'invoice' ?  __('Invoice Number') : __('Quote Number')}}
                </th>
                <th class="text-[14px]  px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Name / Company Name') }}
                </th>
                
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Issue Date') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Due Date') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Status') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Total') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse ($invoices as $invoice)
                <tr wire:key="invoice-{{ $invoice->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('fatture.show',$invoice) }}">{{ $invoice->invoice_number }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $invoice->client->company_name ?? $invoice->client->first_name . ' ' . $invoice->client->last_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $invoice->issue_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $invoice->due_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{-- {{ $invoice->status }} --}}

                        {{-- @if($invoice->status === 'paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Pagata</span>
                        @elseif($invoice->status === 'partially_paid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Parz. Pagata</span>
                        @elseif($invoice->status === 'unpaid')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Non Pagata</span>
                        @endif --}}

                        {{-- BADGE DELLO STATO AGGIORNATI --}}
                            @php
                                $status = $invoice->status;
                                $statusClass = '';
                                if (in_array($status, ['paid', 'accepted'])) $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                elseif (in_array($status, ['unpaid', 'rejected'])) $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                elseif (in_array($status, ['partially_paid', 'sent'])) $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                else $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'; // Per 'draft', 'cancelled'
                            @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">{{ __(ucfirst(str_replace('_',' ',$status))) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        &euro; {{ $invoice->total }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                        <a href="{{ route('fatture.edit',compact('invoice')) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{__('Edit')}}</a>
                        
                        @if($filterType === 'invoice')
                            <a href="{{ route('fatture.pdf',compact('invoice')) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">PDF</a>
                        @endif
                        <x-danger-button 
                            wire:click="deleteInvoice({{ $invoice->id }})"
                            wire:confirm="{{ __('Are you sure you want to delete this invoice?') }}">
                            {{ __('Delete') }}
                        </x-danger-button>

                    </td>
                </tr>
            @empty
                <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            {{-- MESSAGGIO "NON TROVATO" DINAMICO --}}
                            @if($filterType === 'invoice')
                                Nessuna fattura trovata
                            @else
                                Nessun preventivo trovato
                            @endif
                        </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    </div>

    <div class="mt-4 py-2 mx-2">
        {{ $invoices->links() }}
    </div>
   

    

</div>
