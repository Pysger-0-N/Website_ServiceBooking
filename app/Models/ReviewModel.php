<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewModel extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = ['booking_id', 'customer_id', 'provider_id', 'rating', 'comment'];

    public function booking()
    {
        return $this->belongsTo(BookingModel::class, 'booking_id', 'id');
    }
}