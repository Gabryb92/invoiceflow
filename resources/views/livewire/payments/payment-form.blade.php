<div>
    @if ($showModal)
        {{-- Sfondo semitrasparente della modale --}}
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 dark:bg-opacity-70"
            x-data @click.away="$wire.closeModal()" {{-- Permette di chiudere cliccando fuori --}}
            x-on:keydown.escape.window="$wire.closeModal()" {{-- Permette di chiudere con il tasto Esc --}}
        >
            {{-- Contenitore principale della modale --}}
            <div 
                class="w-full max-w-lg p-6 mx-4 bg-white rounded-lg shadow-xl dark:bg-gray-800"
                x-data="{ show: @entangle('showModal') }"
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >

                {{-- Intestazione della modale --}}
                <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Registra un Nuovo Pagamento
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Corpo del form --}}
                <form wire:submit.prevent="savePayment" class="mt-6 space-y-6">
                    <div>
                        <label for="amount_paid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Importo Pagato (€)</label>
                        <input type="number" step="0.01" id="amount_paid" wire:model="amount_paid" class="form-input-style w-full" placeholder="0.00">
                        @error('amount_paid') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Pagamento</label>
                        <input type="date" id="payment_date" wire:model="payment_date" class="form-input-style w-full">
                        @error('payment_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Metodo</label>
                        <select id="payment_method" wire:model="payment_method" class="form-input-style w-full">
                            <option value="">Seleziona un metodo</option>
                            <option value="Bonifico">Bonifico</option>
                            <option value="Carta di Credito">Carta di Credito</option>
                            <option value="PayPal">PayPal</option>
                            <option value="Contanti">Contanti</option>
                        </select>
                         @error('payment_method') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Note</label>
                        <textarea id="notes" wire:model="notes" rows="3" class="form-input-style w-full" placeholder="Dettagli aggiuntivi..."></textarea>
                        @error('notes') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Pulsanti di azione --}}
                    <div class="flex justify-end pt-4 space-x-4">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500">
                            Annulla
                        </button>
                        <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Salva Pagamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>