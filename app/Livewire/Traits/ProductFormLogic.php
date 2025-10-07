<?php

namespace App\Livewire\Traits;

use App\Models\Product;

trait ProductFormLogic
{
    // Proprietà del prodotto
    public $name = '';
    public $description = '';

    public $default_vat_rate = '';

    public $default_unit_price;

    public $default_unit_of_measure= '';

    // Regole di validazione
    public function rules()
    {
        
        return [
            "name" => "required|unique:products,name",
            "default_unit_price" => "required|numeric|min:0",
            "default_vat_rate" => "required|numeric|min:0",
            "default_unit_of_measure" => "nullable|string|max:100"
        ];
    }
}