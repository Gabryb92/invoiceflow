<div>
    <x-slot name="header" class="">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dettagli Cliente') }}
            </h2>
        </div>
    </x-slot>

    
    <div class="py-12 sm:px-6 lg:px-8">
        <div class="space-y-8">

        {{-- CARD DETTAGLIO CLIENTE --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-200">Dettaglio Cliente</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Informazioni anagrafiche e di contatto.</p>
                    </div>
                    <a href="{{ route('clienti.edit',compact('client')) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Edit') }}
                    </a>
                </div>

                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">{{__('Name / Company Name')}}</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-400">
                                {{ $client->company_name ?? $client->first_name . ' ' . $client->last_name }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">Email</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-400">{{$client->email}}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">{{__('Phone')}}</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-400">{{$client->phone}}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">{{__('Vat Number')}}</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-400">{{$client->vat_number}}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">{{__('Fiscal Code')}}</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-400">{{$client->fiscal_code}}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">{{__('Client since')}}</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-400">{{$client->created_at->format('d/m/Y')}}</dd>
                        </div>
                         <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">{{__('Location')}}</dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-400">{{$client->address}} - ({{ $client->province }}) - {{$client->zip_code}} {{$client->country}}</dd>
                        </div>
                         <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">
                                {{ __('Total invoices') }}
                            </dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-400">&euro; {{number_format($total_of_all_invoices,2,',','.')}}</dd>
                         </div>
                         <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">
                                {{ __('Total not paid') }}
                            </dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-gray-400">&euro; {{number_format($total_not_paid,2,',','.')}}</dd>
                         </div>
                        <div class="sm:col-span-3"> {{-- Occupa tutta la larghezza --}}
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-100">{{__('Notes')}}</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-gray-400">
                                {{ $client->notes ?? 'Nessuna nota.' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- CARD TABELLA FATTURE --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-200">Fatture</h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Elenco di tutte le fatture associate al cliente.</p>
                    </div>
                     <a href="#" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Aggiungi Fattura
                    </a>
                </div>

                <div class="mt-6 flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 dark:border-gray-700 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-800">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider ">Numero</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Emissione</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Scadenza</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Importo</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stato</th>
                                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Azioni</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @forelse ($client->invoices as $invoice)
                                            <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"><a href="{{ route('fatture.show',$invoice) }}">{{$invoice->invoice_number}}</a></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{$invoice->issue_date->format('Y-m-d')}}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{$invoice->due_date->format('Y-m-d')}}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">&euro; {{$invoice->total}}</td>
                                            @if ($invoice->status === 'paid')
                                                
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Pagata</span>
                                            </td>
                                            @elseif($invoice->status === 'partially_paid')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">In attesa</span>
                                                </td>
                                            @elseif($invoice->status === 'unpaid')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Non pagata</span>
                                            </td>
                                            @elseif($invoice->status === 'cancelled')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Cancellata</span>
                                            </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('fatture.pdf',$invoice) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Visualizza</a>
                                            </td>
                                        </tr>    
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium ">
                                                    <p class="text-gray-500 dark:text-gray-300">{{__('No invoices found.')}}</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>
</div>