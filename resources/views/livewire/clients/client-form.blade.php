<div>
    @if($client->exists)

    <div class="p-4 sm:p-6 lg:px-12">
       
            <header class="sm:px-6 lg:px-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Edit Client') }}
                </h1>
            </header>
        
    </div>
        
    @else
        <div class="p-4 sm:p-6 lg:px-12">
       
            <header class="sm:px-6 lg:px-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ __('Add Client') }}
                </h1>
            </header>
        
    </div>
        
    @endif
    
    <div class="px-12">
        
        <section class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 text-gray-900 dark:text-gray-100 ">
                    <x-alert />
        
                    <form wire:submit.prevent="save" class="grid gap-4 sm:grid-cols-4 sm:gap-4">
                        <h2 class="sm:col-span-4 mb-4 text-xl font-bold text-gray-900 dark:text-white">{{__('Client Information')}}</h2>
                        {{-- <div class="grid gap-4 sm:grid-cols-4 sm:gap-4"> --}}
                        
        
                            <div class="sm:col-span-2">
                                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('First Name')}}</label>
                                <input type="text" wire:model="first_name" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Last Name') }}</label>
                                <input type="text" wire:model="last_name" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-4">
                                <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Company Name') }}</label>
                                <input type="text" wire:model="company_name" id="company_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @error('company_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Email') }}</label>
                                <input type="email" wire:model="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
                            </div>
                            <div class="sm:col-span-2">
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Phone') }}</label>
                                <input type="phone" wire:model="phone" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" >
                                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
                            </div>
                            <div class="sm:col-span-2">
                                <label for="country" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Country')}}</label>
                                <input type="text" wire:model="country" id="country" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
                            </div>
                            <div class="w-full">
                                <label for="city" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('City')}}</label>
                                <input type="text" wire:model="city" id="city" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
                            </div>
                            <div class="w-full">
                                <label for="province" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Province') }}</label>
                                <select wire:model="province" id="province" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected="">{{ __('Select Province') }}</option>
                                    @foreach ($provinces as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                    
                                </select>
                                @error('province') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
                            </div>
                            <div class="sm:col-span-3">
                                <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Address')}}</label>
                                <input type="text" wire:model="address" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
        
                            <div class="sm:col-span-1">
                                <label for="zip_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Zip Code')}}</label>
                                {{-- <input type="number" wire:model="zip_code" id="zip_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"> --}}
                                <div class="relative">
                                    <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                                            <path d="M8 0a7.992 7.992 0 0 0-6.583 12.535 1 1 0 0 0 .12.183l.12.146c.112.145.227.285.326.4l5.245 6.374a1 1 0 0 0 1.545-.003l5.092-6.205c.206-.222.4-.455.578-.7l.127-.155a.934.934 0 0 0 .122-.192A8.001 8.001 0 0 0 8 0Zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
                                        </svg>
                                    </div>
                                    <input type="text" wire:model="zip_code" id="zip-input" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="12345" pattern="^\d{5}(-\d{4})?$"/>
                                </div>
                                @error('zip_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
                            </div>
                            
                            
                            <div class="sm:col-span-2">
                                <label for="vat_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Vat Number') }}</label>
                                <input type="text" wire:model="vat_number" id="vat_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('vat_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
                            </div>
        
                            <div class="sm:col-span-2">
                                <label for="fiscal_code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Fiscal Code') }}</label>
                                <input type="text" wire:model="fiscal_code" id="fiscal_code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('fiscal_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
                            </div>
                            
        
                            <div class="sm:col-span-4">
                                <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{__('Notes')}}</label>
                                <textarea id="notes" wire:model="notes" rows="8" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"></textarea>
                                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
        
                        {{-- </div> --}}
                        
                        <div class="sm:col-span-4 mt-4 flex justify-end">

                            <x-primary-button class="mr-2  sm:mr-2">
                                {{ __('Save') }}
                            </x-primary-button>
                            <a href="{{ route('clienti.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">{{__('Back')}}</a>
                        </div>
        
                        
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
