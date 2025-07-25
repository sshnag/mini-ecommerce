<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Illuminate\Notifications\DatabaseNotification;


class User extends Authenticatable
{

    use HasFactory, Notifiable, SoftDeletes, HasRoles;
     protected $guard_name = 'web';
     //relation with Order model
     public function orders()
     {
        return $this->hasMany(Order::class);
     }
     //relation with Address model
     public function addresses()
{
    return $this->hasMany(Address::class);
}
    //relation with Cart model

     public function carts(){
        return $this->hasMany(Cart::class);
     }

     //relation with Review model
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
        'profile_image'
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
            $lastUser = User::withTrashed()->orderBy('id', 'desc')->first();
            $nextId = ($lastUser ? $lastUser->id : 0) + 1;
            $user->custom_id = 'USER-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        });

    // Remove or comment out the updated observer to prevent infinite recursion
    /*
    static::updated(function ($user) {
        if ($user->isDirty()) { // Check if any attributes were changed
            $originalRoles = $user->getOriginal('roles') ?? [];
            $currentRoles = $user->getRoleNames()->toArray();

            if ($originalRoles != $currentRoles) {
                $user->forceLogout();
            }
        }
    });
    */
}

    /**
     * Summary of forceLogout
     * forcing the user to log out once the role is changed to admin from user
     * @return void
     */
    public function forceLogout()
    {
        // Invalidate remember token
        $this->setRememberToken(Str::random(60));

        // Delete all active sessions
        DB::table('sessions')
            ->where('user_id', $this['id'])
            ->delete();

        $this->save();
    }

public function isAdmin()
{
    return $this->roles()->whereIn('name', ['admin', 'superadmin'])->exists();
}

}
