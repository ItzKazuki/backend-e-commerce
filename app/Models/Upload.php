<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'user_id' // user who upload that
    ];

    public function product() {
        return $this->hasOne(Product::class);
    }
}
