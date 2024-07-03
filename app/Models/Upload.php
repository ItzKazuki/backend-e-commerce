<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'file_name',
        'user_id' // user who upload that
    ];

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = 'UP-' . strtoupper(uniqid());
            }
        });

        // Delete associated file when Upload is deleted
        static::deleting(function ($model) {
            $filePath = str_replace(Storage::url(''), '', $model->image); // Get the file path relative to the storage disk
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
        });
    }


    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
