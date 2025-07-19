<div>
    <x-slot name="header">
        <div class="flex ml-auto justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl flex justify-end ml-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center flex-row space-x-4">    
            
            
            <a href="{{ route('prodotti.create') }}" class="flex flex-row items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Add Product') }}
            </a>
        </div>
    </div>


    <x-alert />


    <div class="border border-gray-700 rounded-lg overflow-hidden m-2">
        
        <table class="min-w-full divide-y divide-gray-700 text-gray-200">
        <thead class="font-bold bg-gray-800">
            <tr class="text-gray-100">
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Name') }}
                </th>
                <th class="text-[14px]  px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Prezzo di Default') }}
                </th>
                
                <th class="text-[14px] px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                    {{ __('Iva di Default') }}
                </th>
                <th class="text-[14px] px-6 py-3 text-center text-xs font-medium  uppercase tracking-wider">
                    {{ __('Actions') }}
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse ($products as $product)
                <tr wire:key="product-{{ $product->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $product->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        &euro; {{ number_format($product->default_unit_price, 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ number_format($product->default_vat_rate,2, ',','.') }} &percnt;
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2 justify-center">
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{__('Edit')}}</a>
                        <x-danger-button 
                            wire:click="deleteProduct({{ $product->id }})"
                            wire:confirm="{{ __('Are you sure you want to delete this product?') }}">
                            {{ __('Delete') }}
                        </x-danger-button>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Nessun prodotto trovato
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    </div>

    <div class="mt-4 py-2 mx-2">
        {{ $products->links() }}
    </div>


</div>
