<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    //Variabile per mostrare i prodotti archiviati/disponibili
    public $showArchived = false;


    public function archiveProduct($product_id){
        $product = Product::withTrashed()->findOrFail($product_id);
        $product->delete();
        session()->flash('message', 'Product archived successfully.');
    }

    public function forceDelete($product_id){
        $product = Product::withTrashed()->findOrFail($product_id);

        $product->forceDelete();
        session()->flash('message', 'Product permanently deleted successfully.');
    }

    public function restoreProduct($product_id){
        $product = Product::withTrashed()->findOrFail($product_id);

        $product->restore();
        session()->flash('message', 'Product restored successfully.');
    }

    public function render()
    {
        $showArchived = (bool) $this->showArchived;

        $showArchived ?
            $products = Product::onlyTrashed()->orderBy('name')->paginate(10) :
            $products = Product::orderBy('name')->paginate(10);
            

        return view('livewire.products.product-list',compact('products'))->layout('layouts.app');
    }
}
