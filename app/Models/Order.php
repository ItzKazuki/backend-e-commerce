<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $with = ['orderItems', 'payment', 'user', 'address'];

    protected $fillable = [
        'customer_id',
        'payment_id',
        'shipping_address_id',
        'shipping_cost',
        'payment_method',
        'order_status',
        'total_price',
    ];

    const PENDING = 'Pending';
    const PROCESSING = 'Processing';
    const SHIPPED = 'Shipped';
    const DELIVERED = 'Delivered';
    const CANCELLED = 'Cancelled';
    const SHIPPING_COST = 10000;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = 'ORD-' . strtoupper(uniqid()) . '-' . date('Y');
            }
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function address() {
        return $this->belongsTo(Address::class, 'shipping_address_id', 'id');
    }
}
