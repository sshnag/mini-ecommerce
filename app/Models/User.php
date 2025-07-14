<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasFactory, Notifiable, SoftDeletes, HasRoles;
     public function orders()
     {
        return $this->hasMany(Order::class);
     }
     public function addresses()
{
    return $this->hasMany(Address::class);
}

     public function carts(){
        return $this->hasMany(Cart::class);
     }
     public function reviews(){
        return $this->hasMany(Review::class);
     }
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
    protected static function boot()
{
    parent::boot();

    static::creating(function ($user) {
        // Get the last inserted ID
        $lastUser = User::withTrashed()->orderBy('id', 'desc')->first();
        $nextId = ($lastUser ? $lastUser->id : 0) + 1;

        // Format with leading zeros
        $user->custom_id = 'USER-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    });
}
public function isAdmin()
{
    return $this->roles()->whereIn('name', ['admin', 'superadmin'])->exists();
}

}
