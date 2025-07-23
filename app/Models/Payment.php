<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        "invoice_id",
        "amount_paid",
        "payment_date",
        "payment_method",
        "notes"
    ];

    protected $casts = [
        "amount_paid" => "decimal:2",
        "payment_date" => "date",
        "payment_method" => "string",
        "notes" => "string"
    ];


    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
