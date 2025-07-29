<?php

namespace App\Livewire\Products;

use Exception;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ProductForm extends Component
{

    public ?Product $product;

    public $name = '';
    public $description = '';

    public $default_vat_rate = '';

    public $default_unit_price;


    public function mount(Product $product){
        $this->product = $product;

        if($this->product->exists){
            $this->name = $product->name;
            $this->description = $product->description;
            $this->default_vat_rate = $product->default_vat_rate;
            $this->default_unit_price = $product->default_unit_price;
        }
    }

    public function rules(){
        $product_id = $this->product?->id;

        return [
            "name" => "required|unique:products,name," . $product_id,
            "default_vat_rate" => "required|numeric|min:0",
            "default_unit_price" => "required|numeric|min:0"
        ];
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
