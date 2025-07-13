<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public static $productNames = [
        'Sviluppo Sito Web Vetrina',
        'Sviluppo E-commerce',
        'Manutenzione Mensile Sito',
        'Consulenza SEO Tecnica',
        'Ottimizzazione Performance Web',
        'Configurazione Server e Deployment',
        'Sviluppo Funzionalità Custom',
        'Risoluzione Bug Urgente',
        'Formazione e Affiancamento',
        'Restyling Grafico Interfaccia',
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->unique()->randomElement(self::$productNames),
            "description" => $this->faker->optional()->sentence(10),
            "default_unit_price" => $this->faker->randomFloat(2, 1, 1500),
            "default_vat_rate" => 22.00,
        ];
    }
}
