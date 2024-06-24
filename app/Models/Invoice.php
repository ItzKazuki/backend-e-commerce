<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, HasUuids;

    protected $use = [
        'order',
        'invoice'
    ];

    protected $fillable = [
        'payment_id',
        'invoice_number',
        'customer_name',
        'invoice_amount',
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
