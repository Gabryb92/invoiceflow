<div>
    

    <div class="p-4 sm:p-6 lg:p-8">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <header class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Dashboard') }}
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __("A snapshot of your business activity.") }}
                </p>
            </header>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{__("Annual Revenue")}}</p>
                        <p class="mt-1 text-3xl font-semibold tracking-tight">€ {{ number_format($totalRevenue, 2, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{__("Outstanding Amount")}}</p>
                        <p class="mt-1 text-3xl font-semibold tracking-tight">€ {{ number_format($outstandingAmount, 2, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{__("Active Clients")}}</p>
                        <p class="mt-1 text-3xl font-semibold tracking-tight">{{ $activeClientsCount }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{__("Invoices Due")}}</p>
                        <p class="mt-1 text-3xl font-semibold tracking-tight">{{ $dueInvoicesCount }}</p>
                    </div>
                </div>
            </div>

            {{-- Liste Recenti --}}
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            {{ __("Last 5 Invoices") }}
                        </h3>
                        <ul role="list" class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($latestInvoices as $invoice)
                                <li class="flex items-center justify-between py-3">
                                    <div class="flex flex-col">
                                        <a href="{{ route('clienti.show', $invoice->client) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $invoice->client->company_name ?? $invoice->client->first_name . ' ' . $invoice->client->last_name }}
                                        </a>
                                        <a href="{{ route('fatture.show', $invoice) }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">
                                            {{ $invoice->invoice_number }}
                                        </a>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">€ {{ number_format($invoice->total, 2, ',', '.') }}</p>
                                        {{-- Logica per lo stato --}}
                                        
                                        @php
                                            $status = $invoice->status;
                                            $statusClass = '';
                                            if (in_array($status, ['paid', 'accepted'])) $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                            elseif (in_array($status, ['unpaid', 'rejected'])) $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                            elseif (in_array($status, ['partially_paid', 'sent'])) $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                            else $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'; // Per 'draft', 'cancelled'
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">{{ __(ucfirst(str_replace('_',' ',$status))) }}</span>
                    
                                    </div>
                                </li>
                            @empty
                                <li class="py-3 text-sm text-gray-500 dark:text-gray-400">{{__("No Invoices Found")}}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            {{ __("Last 5 Added Clients") }}
                        </h3>
                        <ul role="list" class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                             @forelse ($latestClients as $client)
                                <li class="py-3 flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($client->company_name ?? $client->first_name . ' ' . $client->last_name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('clienti.show', $client) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate hover:underline">
                                            {{ $client->company_name ?? $client->first_name . ' ' . $client->last_name }}
                                        </a>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ __("Client since") }} {{ $client->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </li>
                            @empty
                                <li class="py-3 text-sm text-gray-500 dark:text-gray-400">{{__("No Recently Added Clients")}}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Spazio per il Grafico --}}
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        {{ __("Revenue Trend (Last 6 Months)") }}
                    </h3>
                    <div class="mt-4 h-80 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex items-center justify-center">
                        <canvas id=revenueChart>
                            
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>