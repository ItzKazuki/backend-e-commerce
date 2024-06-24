<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $use = [
        'user',
        'invoice'
    ];

    protected $fillable = [
        'order_id',
        'user_id',
        'invoice_number',
        'payment_method',
        'payment_status',
        'payment_amount',
    ];

    const PENDING = 'pending';
    const COMPLETED = 'completed';
    const FAILED = 'failed';
    const CANCELLED = 'cancelled';

    const MIDTRANS_PAYMENTS = [
        // "credit_card",
        "gopay",
        "shopeepay",
        // "permata_va",
        // "bca_va",
        // "bni_va",
        // "bri_va",
        // "echannel",
        // "other_va",
        "Indomaret",
        "alfamart",
        // "akulaku"
    ];
    const PAYMENT_METHODS = ['cod', 'spay', 'qris', 'gopay'];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = 'PYMT-' . strtoupper(uniqid()) . '-' . date('Y');
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
