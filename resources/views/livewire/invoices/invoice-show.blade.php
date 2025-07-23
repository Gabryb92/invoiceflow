<div>
    <x-slot name="header">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dettaglio Fattura') }}
            </h2>
        </div>
    </x-slot>

    {{-- Contenitore standard di Breeze per la larghezza completa --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- "Card" principale che contiene tutta la fattura --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 lg:p-10">

                    {{-- 1. Intestazione: Titolo, Numero Fattura e Azioni --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Fattura</h1>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                                Numero: <span class="text-gray-800 dark:text-gray-200">{{ $invoice->invoice_number }}</span>
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex space-x-2">
                            <a href="{{ route('fatture.edit', $invoice) }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 dark:text-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                               <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Modifica
                            </a>
                            <a href="{{ route('fatture.pdf', $invoice) }}" target="_blank" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                Download PDF
                            </a>
                        </div>
                    </div>

                    {{-- 2. Dettagli Azienda e Cliente --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8">
                        <div>
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">DA</h2>
                            <div class="mt-3 text-gray-800 dark:text-gray-200">
                                <p class="font-bold">Gabriele Bonazza - Developer</p>
                                <p class="text-sm">Viale Giacomo Leopardi 100, 44029, Lido degli Estensi (FE)</p>
                                <p class="text-sm">P.IVA: 12345678901</p>
                                <p class="text-sm">info@tuasocieta.it</p>
                            </div>
                        </div>
                        <div >
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">A</h2>
                            <div class="mt-3 text-gray-800 dark:text-gray-200">
                                <p class="font-bold">{{ $invoice->client->company_name ?? $invoice->client->first_name . ' ' . $invoice->client->last_name }}</p>
                                <p class="text-sm">{{ $invoice->client->address }}</p>
                                <p class="text-sm">{{ $invoice->client->vat_number }}</p>
                                <p class="text-sm">{{ $invoice->client->email }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Metadati Fattura: Data, Scadenza e Stato --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                        <div class="text-center sm:text-left mb-4 sm:mb-0">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Data Fattura</p>
                            <p class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $invoice->issue_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-center sm:text-left mb-4 sm:mb-0">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Data Scadenza</p>
                            <p class="text-base font-bold text-gray-900 dark:text-gray-100">{{ $invoice->due_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-center sm:text-left">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stato</p>
                            @if($invoice->status === 'paid')
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 dark:text-green-100 dark:bg-green-500/30 rounded-full">Pagata</span>
                            @elseif($invoice->status === 'partially_paid')
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 dark:text-yellow-100 dark:bg-yellow-500/30 rounded-full">Pagata Parzialmente</span>
                            @elseif($invoice->status === 'cancelled')
                                 <span class="inline-flex items-center px-3 py-1 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 dark:text-gray-100 dark:bg-gray-500/30 rounded-full">Annullata</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 dark:text-red-100 dark:bg-red-500/30 rounded-full">Non Pagata</span>
                            @endif
                        </div>
                    </div>

                    {{-- 4. Tabella degli Articoli/Servizi --}}
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300 sm:pl-2">{{__('Description')}}</th>
                                            <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">{{__('Quantity')}}</th>
                                            <th scope="col" class="px-3 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">{{__('Unit Price')}}</th>
                                            <th scope="col" class="px-3 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">{{__('Vat')}} (&percnt;)</th>
                                            <th scope="col" class="py-3.5 pl-3 pr-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300 sm:pr-2">{{__('Total')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                        @foreach ($invoice->invoiceItems as $item)
                                        <tr>
                                            <td class="py-4 pl-4 pr-3 text-sm sm:pl-0">
                                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $item->description }}</p>
                                            </td>
                                            <td class="px-3 py-4 text-sm text-center text-gray-500 dark:text-gray-400">{{ $item->quantity }}</td>
                                            <td class="px-3 py-4 text-sm text-right text-gray-500 dark:text-gray-400">€ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                            <td class="px-3 py-4 text-sm text-right text-gray-500 dark:text-gray-400">{{ number_format($item->vat_rate, 2, ',', '.') }}%</td>
                                            <td class="py-4 pl-3 pr-4 text-sm text-right font-medium text-gray-800 dark:text-gray-200 sm:pr-0">€ {{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- 5. Totali Fattura --}}
                    <div class="mt-8 flex justify-end">
                        <div class="w-full max-w-sm">
                            <dl class="space-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600 dark:text-gray-400">{{ __('Subtotal') }}:</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">€ {{ number_format($invoice->subtotal, 2, ',', '.') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600 dark:text-gray-400">{{ __('Vat Amount') }}:</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">€ {{ number_format($invoice->vat_amount, 2, ',', '.') }}</dd>
                                </div>
                                <div class="flex justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <dt class="text-base font-bold text-gray-900 dark:text-gray-100">{{ __('Total not paid') }}:</dt>
                                    <dd class="text-base font-bold text-indigo-600 dark:text-indigo-400">€ {{ number_format($invoice->total, 2, ',', '.') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- 6. Note a piè di pagina --}}
                    @if($invoice->notes)
                    <div class="mt-10 pt-6 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
                        <h3 class="font-semibold text-gray-700 dark:text-gray-300">{{__('Notes')}}</h3>
                        <p class="mt-2">
                           {{ $invoice->notes }}
                        </p>
                    </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>