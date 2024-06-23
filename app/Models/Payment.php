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
        'user_id',
        'invoice_number',
        'payment_method',
        'payment_status',
        'payment_amount',
    ];

    const PENDING = 'pending';
    const COMPLETED = 'completed';
    const FAILED = 'failed';

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

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
