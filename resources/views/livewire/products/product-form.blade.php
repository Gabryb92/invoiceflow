<div>
    @if($product->exists)
    <x-slot name="header">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Product') }}
            </h2>
        </div>
    </x-slot>
    @else
    <x-slot name="header">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Product') }}
            </h2>
        </div>
    </x-slot>
    @endif


    <div class="p-12">
        <section class="w-full mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 ">
                    <x-alert />

                    <form wire:submit.prevent="save" class="grid sm:grid-cols-2">
                        <h2 class="sm:col-span-2 mb-4 text-xl font-bold text-gray-900 dark:text-white">{{__('Product Information')}}</h2>
                        

                        <div class="grid gap-4 sm:grid-cols-4 sm:gap-4 sm:col-span-2">
                        

                            <div class="sm:col-span-2">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Name')}}</label>
                                <input type="text" wire:model="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="default_unit_of_measure" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Unit of measure')}}</label>
                                <input type="text" wire:model="default_unit_of_measure" id="default_unit_of_measure" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="es. ora, mese, kg">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Description')}}</label>
                                <input type="text" wire:model="description" id="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="default_vat_rate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Default Vat Rate') }}</label>
                                <input type="text" wire:model="default_vat_rate" id="default_vat_rate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="22.00 &percnt;">
                                @error('default_vat_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            

                            
                            <div class="sm:col-span-2">
                                <label for="default_unit_price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Default Unit Price') }}</label>
                                <input type="text" wire:model="default_unit_price" id="default_unit_price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="&euro; 0.00">
                                @error('default_unit_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        
                            
                        </div>


                            <div class="sm:col-span-2 mt-4 flex justify-end">
                                {{-- <x-primary-button class="mt-4 mr-2 sm:mt-4 sm:mr-2"> --}}
                                {{-- <x-primary-button class="sm:col-span-1 mt-4 mr-2 sm:mt-4 sm:mr-2"> --}}
                                <x-primary-button class="mr-2  sm:mr-2">
                                    {{ __('Save') }}
                                </x-primary-button>

                                <a href="{{ route('prodotti.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">{{__('Back')}}</a>
                            </div>

                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
