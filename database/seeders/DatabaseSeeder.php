<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use Database\Factories\ProductFactory;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Schema::disableForeignKeyConstraints();

        Client::truncate();
        Invoice::truncate();
        InvoiceItem::truncate();
        Product::truncate();

        Schema::enableForeignKeyConstraints();
        
        foreach(ProductFactory::$productNames as $name){
            Product::factory()->create(["name" => $name]);
        }

        Client::factory(25)->create();

        Invoice::factory(50)->has(InvoiceItem::factory()->count(rand(1, 5)))->create();
    }
}
