<div>

    @php
        $confirmMessage = __('Are you sure you want to convert this quote to an invoice? This action is irreversible.');
    @endphp

    @if($invoice->exists)
        <x-slot name="header">
            <div class="flex ml-auto justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{$type === 'invoice' ? __('Edit Invoice') : __('Edit Quote') }}
                </h2>
            </div>
        </x-slot>
    @else
        <x-slot name="header">
            <div class="flex ml-auto justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{$type === 'invoice' ? __('Add Invoice') : __('Add Quote') }}
                </h2>
            </div>
        </x-slot>
    @endif


    <div class="p-4 sm:p-6 lg:p-12">
        <section class="w-full mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 ">
                    <x-alert />

                    <form wire:submit.prevent="save" class="grid grid-cols-1 sm:grid-cols-2">
                        <h2 class="sm:col-span-2 mb-4 text-xl font-bold text-gray-900 dark:text-white">{{$type === 'invoice' ?__('Invoice Dates') : __('Quote Dates')}}  {{$invoice->exists ? "- " . $this->invoice_number : ""}}</h2>
                        

                        <div class="grid gap-4 sm:grid-cols-4 sm:gap-4 sm:col-span-2">
                        

                            
                            <div class="sm:col-span-2">
                                <label for="client" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Client') }}</label>
                                <select wire:model="client_id" id="client" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected="">{{ __('Select Client') }}</option>
                                    @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->company_name ?? $client->last_name . ' ' . $client->first_name }}</option>
                                    @endforeach
                                    
                                </select>
                                @error('client_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                            </div>
                            <div class="sm:col-span-2">
                                <label for="issue_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Issue Date') }}</label>
                                <input type="date" wire:model="issue_date" id="issue_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @error('issue_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Status') }}</label>
                                <select wire:model="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    @if($type === 'invoice')
                                        <option value="unpaid">{{__('Unpaid')}}</option>
                                        <option value="partially_paid">{{__('Partially Paid')}}</option>
                                        <option value="paid">{{__('Paid')}}</option>
                                        <option value="cancelled">{{__('Cancelled')}}</option>
                                    @else {{-- Se è un preventivo ('quote') --}}
                                        <option value="draft">{{__('Draft')}}</option>
                                        <option value="sent">{{__('Sent')}}</option>
                                        <option value="accepted">{{__('Accepted')}}</option>
                                        <option value="rejected">{{__('Rejected')}}</option>
                                        <option value="cancelled">{{__('Cancelled')}}</option>
                                    @endif
                                    
                                </select>
                                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                            </div>
                            <div class="sm:col-span-2">
                                <label for="due_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Due Date') }}</label>
                                <input type="date" wire:model="due_date" id="due_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        
                            
                                    
                            

                            <div class="sm:col-span-4">
                                <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Notes')}}</label>
                                <textarea id="notes" wire:model="notes" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"></textarea>
                                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            

                            

                        </div>


                        <h3 class="sm:col-span-2 text-lg font-semibold text-gray-900 dark:text-white mt-8 mb-4">
                            {{ __('Invoice Items') }}
                        </h3>

                        @error('invoiceItems')
                            <div class="sm:col-span-2 p-4 mb-4 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="sm:col-span-2 border border-gray-200 dark:border-gray-700 rounded-lg overflow-x-auto sm:overflow-hidden mb-4">
                            <div class="overflow-x-auto sm:overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-700 text-gray-200">
                                    <thead class="font-bold bg-gray-800">
                                        <tr class="text-gray-100">
                                            <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                                                {{ __('Description') }}
                                            </th>
                                            <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                                                {{ __('Unit of measure') }}
                                            </th>
                                            <th class="text-[14px]  px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                                                {{ __('Quantity') }}
                                            </th>
                                            
                                            <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                                                {{ __('Unit Price') }}
                                            </th>
                                            <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                                                {{ __('Vat') }}
                                            </th>
                                            <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                                                {{ __('Subtotal') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        @forelse ($invoiceItems as $index => $item)
                                            <tr wire:key="item-{{ $index }}">
                                                {{-- Campo Descrizione --}}
                                                <td class="px-2 py-4 whitespace-nowrap">
                                                    <input type="text" wire:model.live="invoiceItems.{{ $index }}.description"
                                                    @if(isset($item['type']) && in_array($item['type'], ['discount', 'shipping'])) disabled @endif 
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                                                    @error('invoiceItems.' . $index . '.description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                </td>

                                                <td class="px-2 py-4 whitespace-nowrap">
                                                    <input type="text" wire:model.live="invoiceItems.{{ $index }}.unit_of_measure" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    @error('invoiceItems.' . $index . '.unit_of_measure') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                </td>

                                                {{-- Campo Quantità --}}

                                                <td class="px-1 py-4 whitespace-nowrap min-w-[80px]">
                                                    <input type="number" step="0.01" wire:model.live="invoiceItems.{{ $index }}.quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                                                    @error('invoiceItems.' . $index . '.quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                </td>

                                                {{-- Campo Prezzo Unitario --}}
                                                <td class="px-1 py-4 whitespace-nowrap min-w-[100px]">
                                                    <input type="number" step="0.01" wire:model.live="invoiceItems.{{ $index }}.unit_price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                                                    @error('invoiceItems.' . $index . '.unit_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                                </td>
                                                {{-- Campo Iva --}}
                                                <td class="px-1 py-4 whitespace-nowrap min-w-[80px]">
                                                    <input type="number" wire:model.live="invoiceItems.{{ $index }}.vat_rate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                                                    @error('invoiceItems.' . $index . '.vat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                                </td>
                                                {{-- Subtotale di riga  --}}

                                                <td class="text-center px-1 py-4 whitespace-nowrap min-w-[100px]">
                                                    {{ number_format( (float)($item['quantity'] ?? 0) * (float)($item['unit_price'] ?? 0), 2, ',', '.') }}
                                                </td>

                                                {{-- Pulsante rimuovi --}}

                                                <td class="px-1 py-4 whitespace-nowrap min-w-[70px]">
                                                    <x-danger-button wire:click.prevent="removeInvoiceItem({{$index}})">{{__('Remove')}}</x-danger-button>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-6 whitespace-nowrap text-center">
                                                    {{ __('Add a voice to begun') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="sm:col-span-2 mb-4">
                            <div class="flex justify-between items-center mt-10 mb-4">
                                 <h3 class="text-lg font-semibold text-gray-900 dark:text-white ">
                                    {{ __('Add Products') }}
                                </h3>

                                <button type="button" wire:click="$dispatch('openProductModal')" class=" px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150">{{__('Add Product')}}</button>
                            </div>
                           

                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                            {{-- Loop through products --}}
                            @foreach ($products as $product)
                                <div class="p-2 bg-gray-50 border border-gray-500 dark:bg-gray-800 rounded-lg flex items-center justify-between">
                                    <span class="font-medium text-sm text-gray-800 dark:text-gray-200">{{ $product->name }}</span>
                                    
                                    {{-- Questo pulsante chiama il metodo che abbiamo già creato --}}
                                    <button type="button" wire:click="addInvoiceItem({{ $product->id }})" class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded hover:bg-blue-600">
                                        &#43; {{ __("Add") }}
                                    </button>
                                </div>
                            @endforeach
                                <div class="p-2 bg-gray-50 border border-gray-500 dark:bg-gray-800 rounded-lg flex items-center justify-between">
                                        <span class="font-medium text-sm text-gray-800 dark:text-gray-200">{{__('Discount')}}</span>
                                        
                                        
                                        <button type="button" wire:click.prevent="addDiscountItem" class="px-3 py-1 bg-purple-500 text-white text-xs font-bold rounded hover:bg-purple-600">
                                            &#43; {{ __("Add") }}
                                        </button>

                                        
                                </div>

                                <div class="p-2 bg-gray-50 border border-gray-500 dark:bg-gray-800 rounded-lg flex items-center justify-between">
                                        <span class="font-medium text-sm text-gray-800 dark:text-gray-200">{{__('Shipping')}}</span>
                                        
                                        
                                        <button type="button" wire:click.prevent="addShippingItem" class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded hover:bg-yellow-600">
                                            &#43; {{ __("Add") }}
                                        </button>

                                        
                                </div>
                            </div>

                        </div>
                        

                        <div class="sm:col-span-2 grid grid-cols-1 mt-8 my-4 pt-2">

                            <h2 class="sm:col-span-2 mb-4 text-xl font-bold text-gray-900 dark:text-white">{{__('Resume')}}</h2>
                                
                                {{-- RIGA IMPONIBILE --}}
                                <div class="sm:col-span-2 flex justify-between items-center">
                                    <span class=" text-gray-600 dark:text-gray-400">Imponibile (Subtotale):</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        &euro; {{ number_format($this->calculatedTotals['subtotal'], 2, ',', '.') }}
                                    </span>
                                </div>

                                {{-- RIGA IVA --}}
                                <div class="sm:col-span-2 flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Totale IVA:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        &euro; {{ number_format($this->calculatedTotals['vat_amount'], 2, ',', '.') }}
                                    </span>
                                </div>

                                {{-- Puoi aggiungere qui Sconti e Spedizioni se vuoi mostrarli nel riepilogo --}}
                                
                                {{-- RIGA TOTALE FINALE --}}
                                <div class="sm:col-span-1 flex justify-between items-center text-xl border-t border-gray-300 dark:border-gray-600 mt-2 pt-2">
                                    <span class="font-bold text-gray-900 dark:text-white">TOTALE</span>
                                    <span class="font-bold text-gray-900 dark:text-white">
                                        &euro; {{ number_format($this->calculatedTotals['total'], 2, ',', '.') }}
                                    </span>
                                </div>

                            </div>

                            <div class="sm:col-span-2 mt-4 flex justify-end">
                                <div>
                                    @if($invoice->exists && $type === 'quote')
                                        <x-success-button type="button" wire:click="convertToInvoice" wire:confirm="{{ $confirmMessage }}" >
                                            {{ __('Convert To Invoice') }}
                                        </x-success-button>
                                    @endif
                                </div>
                                <x-primary-button class="ml-2 mr-2  sm:mr-2">
                                    {{ __('Save') }}
                                </x-primary-button>

                                <a href="{{ route('fatture.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">{{__('Back')}}</a>
                            </div>

                    </form>
                </div>
            </div>
        </section>
    </div>
    
<livewire:products.product-modal />

</div>