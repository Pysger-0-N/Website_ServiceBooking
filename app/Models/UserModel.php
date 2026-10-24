<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'role', 'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function providerProfile()
    {
        return $this->hasOne(ProviderProfileModel::class, 'user_id', 'id');
    }

    public function bookingsAsCustomer()
    {
        return $this->hasMany(BookingModel::class, 'customer_id', 'id');
    }

    public function bookingsAsProvider()
    {
        return $this->hasMany(BookingModel::class, 'provider_id', 'id');
    }

    public function providerCategories()
    {
        return $this->belongsToMany(CategoryModel::class, 'provider_categories', 'user_id', 'category_id');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }


    public function isAdmin() { return $this->role === 'admin'; }
    public function isProvider() { return $this->role === 'provider'; }
    public function isCustomer() { return $this->role === 'customer'; }
}
