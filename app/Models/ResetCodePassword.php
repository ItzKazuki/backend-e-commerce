<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetCodePassword extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'email',
        'code',
        'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function isExpired()
    {
        if (now() > $this->created_at->addHour()) {
            $this->delete();
            return true;
        }

        return false;
    }
}
