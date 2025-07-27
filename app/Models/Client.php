<?php

namespace App\Models;

use App\Policies\ClientPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

#[UsePolicy(ClientPolicy::class)]
class Client extends Model
{
    use HasFactory,SoftDeletes,Notifiable;

    protected $fillable =
        [
            "first_name",
            "last_name",
            "company_name",
            "email",
            "phone",
            "address",
            "city",
            "zip_code",
            "province",
            "country",
            "vat_number",
            "fiscal_code",
            "is_anonymized",
            "notes"
        ];

    //Un cliente ha molte fatture
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
