<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'shipping_address',
        'shipping_cost',
        'payment_method',
        'order_status',
        'total_price',
    ];

    public $PENDING = 'pending';
    public $PROCESSING = 'processing';
    public $SHIPPED = 'shipped';
    public $DELIVERED = 'delivered';
    public $CANCELLED = 'cancelled';

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
