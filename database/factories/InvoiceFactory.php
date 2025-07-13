<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 1. Generiamo un subtotale realistico
        $subtotal = $this->faker->randomFloat(2, 50, 5000);
        // 2. Calcoliamo l'IVA basandoci sul subtotale
        $vat_amount = $subtotal * 0.22; 
        // 3. Generiamo spedizione e sconto
        $shipping_amount = $this->faker->randomElement([0, 15, 25, 50]);
        $discount_amount = $this->faker->randomElement([0, 10, 20, 50]);

        $client = Client::inRandomOrder()->first() ?? Client::factory()->create();

        return [
        'client_id' => $client->id,
        'invoice_number' => $this->faker->unique()->numerify('INV-#####'),
        'issue_date' => $this->faker->date(),
        'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        
        'subtotal' => $subtotal,
        'vat_amount' => $vat_amount,
        'shipping_amount' => $shipping_amount,
        'discount_amount' => $discount_amount,

        'total' => function (array $attributes) {
            return ($attributes['subtotal'] + $attributes['vat_amount'] + $attributes['shipping_amount']) - $attributes['discount_amount'];
        },
        
        'status' => $this->faker->randomElement(['unpaid', 'partially_paid', 'paid', 'cancelled']),
        'notes' => $this->faker->optional()->paragraph(),
    ];
    }
}
