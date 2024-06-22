<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $with = ['upload', 'seller'];

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
