<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProviderProfileModel extends Model
{
    use HasFactory;

    protected $table = 'provider_profiles';

    protected $fillable = [
        'user_id', 'bio', 'experience_years', 'certifications',
        'service_radius_km', 'status',
        'available_days', 'available_from', 'available_until',
        'location_address', 'location_lat', 'location_lng',
    ];

    protected $casts = [
        'available_days' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            CategoryModel::class,
            'provider_categories',
            'user_id',
            'category_id'
        );
    }
}
