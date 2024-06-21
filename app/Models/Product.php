<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_desc',
        'price',
        'stock',
        'brand',
        'seller_id', // ini user yang role nya seller
        'upload_id'
    ];

    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function upload() {
        return $this->belongsTo(Upload::class, 'upload_id');
    }
}
