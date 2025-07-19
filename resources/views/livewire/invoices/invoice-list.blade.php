<div>
    <x-slot name="header">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Invoices') }}
            </h2>
        </div>
    </x-slot>

    

    <div class="max-w-7xl flex justify-end ml-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center flex-row space-x-4">    
            
            
            <a href="{{ route('fatture.create') }}" class="flex flex-row items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Add Invoice') }}
            </a>
        </div>
    </div>


    <x-alert />
    

    <div class="border border-gray-700 rounded-lg overflow-hidden m-2">
        
        <table class="min-w-full divide-y divide-gray-700 text-gray-200">
        <thead class="font-bold bg-gray-800">
            <tr class="text-gray-100">
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Invoice Number') }}
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
                        {{ $invoice->invoice_number }}
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
                        {{ $invoice->status }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        &euro; {{ $invoice->total }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                        <a href="{{ route('fatture.edit',compact('invoice')) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{__('Edit')}}</a>
                        <a href="{{ route('fatture.pdf',compact('invoice')) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 active:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">PDF</a>
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
                        Nessuna fattura trovata
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
