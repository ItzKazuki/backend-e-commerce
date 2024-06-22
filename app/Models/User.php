<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'about',
        'address'
    ];

    /**
     *  This var using to define role user, default is customer
     */
    const ADMIN = 'admin';
    const SELLER = 'seller';
    const CUSTOMER = 'customer';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function booted()
    {
        static::creating(fn ($user) => $user->forceFill(['avatar_url' => "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) )]));
        static::updated(fn ($user) => $user->forceFill(['avatar_url' => "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) )]));
    }

    public function product() {
        return $this->hasMany(Product::class);
    }

    public function upload() {
        return $this->hasMany(Upload::class);
    }
}
