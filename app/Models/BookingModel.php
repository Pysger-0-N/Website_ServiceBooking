<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingModel extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'customer_id', 'provider_id', 'service_id',
        'booking_date', 'booking_time', 'location_address',
        'location_lat', 'location_lng', 'notes',
        'total_price', 'status'
    ];

    public function customer()
    {
        return $this->belongsTo(UserModel::class, 'customer_id', 'id');
    }

    public function provider()
    {
        return $this->belongsTo(UserModel::class, 'provider_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(ServiceModel::class, 'service_id', 'id');
    }

    public function review()
    {
        return $this->hasOne(ReviewModel::class, 'booking_id', 'id');
    }
}