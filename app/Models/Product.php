<?php

namespace App\Models;

use App\Policies\ProductPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UsePolicy(ProductPolicy::class)]
class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ["name", "description", "default_unit_price", "default_vat_rate","default_unit_of_measure"];

    protected $casts = [
        'default_unit_price' => 'decimal:2',
        'default_vat_rate' => 'decimal:2',
    ];

    //Un prodotto può essere associato a molte fatture tramite gli articoli della fattura
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
