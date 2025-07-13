<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        return [
            "invoice_id" => \App\Models\Invoice::factory(),
            "product_id" => $product->id,

            "description" => $product->name,
            "unit_price" => $product->default_unit_price,
            "vat_rate" => $product->default_vat_rate,
            "quantity" => $this->faker->randomFloat(2,1,100),
            
            
        ];
    }
}
