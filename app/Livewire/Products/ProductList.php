<?php

namespace App\Livewire\Products;

use Exception;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ProductList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    //Variabile per mostrare i prodotti archiviati/disponibili
    public $showArchived = false;

    public $search = "";


    public function archiveProduct(int $product_id){
        try{

            $product = Product::findOrFail($product_id);
            $product->delete();
            session()->flash('message', 'Product archived successfully.');
        } catch (Exception $e) {
            session()->flash('error', "An error occurred during the archiving. Please try again later.");
            Log::error($e->getMessage());
        }
    }

    public function forceDelete(int $product_id){
        try{

            $product = Product::withTrashed()->findOrFail($product_id);
    
            $product->forceDelete();
            session()->flash('message', 'Product permanently deleted successfully.');
        } catch (Exception $e) {
            session()->flash('error', "An error occurred during the deletion. Please try again later.");
            Log::error($e->getMessage());
        }
    }

    public function restoreProduct(int $product_id){
        try{

            $product = Product::withTrashed()->findOrFail($product_id);
    
            $product->restore();
            session()->flash('message', 'Product restored successfully.');
        } catch (Exception $e) {
            session()->flash('error', "An error occurred during the restore. Please try again later.");
            Log::error($e->getMessage());
        }
    }

    public function render()
    {
        $query = Product::query();

        $showArchived = (bool) $this->showArchived;

        if($showArchived){
            $query->onlyTrashed();
        }

        $query->when($this->search, function ($q) {

            $q->where(function ($subQuery) {
                $searchItem = '%' . $this->search . '%';
                $subQuery->where('name', 'like', $searchItem);

            });
        });


        $products = $query->latest()->paginate(10);
            

        return view('livewire.products.product-list',compact('products'))->layout('layouts.app');
    }
}
