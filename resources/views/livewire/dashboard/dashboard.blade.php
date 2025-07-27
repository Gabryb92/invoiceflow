{{-- <div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <div class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <main>
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <header class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                                Dashboard
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Una visione d'insieme della tua attività.
                            </p>
                        </header>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Fatturato Annuale</p>
                                    <p class="mt-1 text-3xl font-semibold tracking-tight">€ 125.640,00</p>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Importo da Incassare</p>
                                    <p class="mt-1 text-3xl font-semibold tracking-tight">€ 12.350,50</p>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Clienti Attivi</p>
                                    <p class="mt-1 text-3xl font-semibold tracking-tight">87</p>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Fatture in Scadenza</p>
                                    <p class="mt-1 text-3xl font-semibold tracking-tight">12</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Ultime 5 Fatture
                                    </h3>
                                    <ul role="list" class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                                        <li class="flex items-center justify-between py-3">
                                            <div class="flex flex-col">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Mario Rossi S.r.l.</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">#FATT-2025-07-25</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">€ 1.200,00</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    Pagata
                                                </span>
                                            </div>
                                        </li>
                                        <li class="flex items-center justify-between py-3">
                                            <div class="flex flex-col">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Web Solutions S.p.A.</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">#FATT-2025-07-24</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">€ 850,00</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                    In Attesa
                                                </span>
                                            </div>
                                        </li>
                                        <li class="flex items-center justify-between py-3">
                                            <div class="flex flex-col">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Studio Legale Bianchi</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">#FATT-2025-07-23</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">€ 2.500,00</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                    Scaduta
                                                </span>
                                            </div>
                                        </li>
                                        <li class="flex items-center justify-between py-3">
                                            <div class="flex flex-col">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Digital Marketing Agency</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">#FATT-2025-07-22</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">€ 500,00</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    Pagata
                                                </span>
                                            </div>
                                        </li>
                                        <li class="flex items-center justify-between py-3">
                                            <div class="flex flex-col">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Ristorante La Brace</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">#FATT-2025-07-21</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">€ 150,00</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                    In Attesa
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Ultimi 5 Clienti Aggiunti
                                    </h3>
                                    <ul role="list" class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
                                        <li class="py-3 flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Luca+Verdi&color=7F9CF5&background=EBF4FF" alt="">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">Luca Verdi</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Aggiunto il 24/07/2025</p>
                                            </div>
                                        </li>
                                        <li class="py-3 flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Giulia+Neri&color=7F9CF5&background=EBF4FF" alt="">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">Giulia Neri</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Aggiunto il 23/07/2025</p>
                                            </div>
                                        </li>
                                        <li class="py-3 flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Marco+Gialli&color=7F9CF5&background=EBF4FF" alt="">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">Marco Gialli</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Aggiunto il 22/07/2025</p>
                                            </div>
                                        </li>
                                        <li class="py-3 flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Sara+Azzurri&color=7F9CF5&background=EBF4FF" alt="">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">Sara Azzurri</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Aggiunto il 21/07/2025</p>
                                            </div>
                                        </li>
                                        <li class="py-3 flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Paolo+Bruno&color=7F9CF5&background=EBF4FF" alt="">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">Paolo Bruno</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Aggiunto il 20/07/2025</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                                    Andamento Fatturato (Ultimi 6 Mesi)
                                </h3>
                                <div class="mt-4 h-80 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex items-center justify-center">
                                    <p class="text-gray-500 dark:text-gray-400">Il grafico verrà visualizzato qui.</p>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
</div> --}}


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
                                        @if($invoice->status === 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">Pagata</span>
                                        @elseif($invoice->status === 'partially_paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Parz. Pagata</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">Non Pagata</span>
                                        @endif
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