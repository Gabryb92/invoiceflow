<?php

namespace App\Livewire\Products;

use App\Livewire\Traits\ProductFormLogic;
use Exception;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ProductForm extends Component
{

    use ProductFormLogic;

    public ?Product $product;

    


    public function mount(Product $product){
        $this->product = $product;

        if($this->product->exists){
            $this->name = $product->name;
            $this->default_unit_of_measure = $product->default_unit_of_measure;
            $this->description = $product->description;
            $this->default_vat_rate = $product->default_vat_rate;
            $this->default_unit_price = $product->default_unit_price;
        }
    }

    

    public function rules(){
        $rules = $this->getRulesFromTrait();

        $productId = $this->product?->id;

        $rules['name'] = "required|unique:products,name," . $productId;

        return $rules;
    }

    protected function getRulesFromTrait():array{
        return (new class {
            use ProductFormLogic;
        })->rules();
    }

    public function save(){
        $validatedData = $this->validate();

        try{

            if($this->product->exists){
                $this->product->update($validatedData);
                session()->flash('message', "Product updated successfully.");
            } else {
                Product::create($validatedData);
                session()->flash('message', "Product created successfully.");
                $this->reset();

                $this->product = new Product();
            } 
        } catch (Exception $e) {
            session()->flash('error', "An error occurred while saving, please try again later.");
            Log::error($e->getMessage());
        }

    }

    public function render()
    {
        return view('livewire.products.product-form')->layout('layouts.app');
    }
}
