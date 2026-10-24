<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = ['category_id', 'name', 'description', 'base_price', 'unit'];

    public function category()
    {
        return $this->belongsTo(CategoryModel::class, 'category_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(BookingModel::class, 'service_id', 'id');
    }
}