<?php

namespace App\Livewire\Products;

use Exception;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Livewire\Traits\ProductFormLogic;

class ProductModal extends Component
{
    use ProductFormLogic;

    public bool $showModal = false;

    #[On('openProductModal')]
    public function openModal(){
        $this->reset();
        $this->showModal = true;
    }

    public function closeModal(){
        $this->showModal = false;
    }

    public function save(){
        $validatedData = $this->validate();

        try{
            $newProduct = Product::create($validatedData);
            $this->dispatch('productCreated', productId: $newProduct->id);
            $this->closeModal();
        } catch (Exception $e) {
            session()->flash('error', "An error occurred while saving, please try again later.");
            Log::error($e->getMessage());
        }
        
    }
    
    public function render()
    {
        return view('livewire.products.product-modal');
    }
}
