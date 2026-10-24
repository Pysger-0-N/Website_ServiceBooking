<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name', 'description'];

    public function services()
    {
        return $this->hasMany(ServiceModel::class, 'category_id', 'id');
    }

    public function providers()
    {
        return $this->belongsToMany(UserModel::class, 'provider_categories', 'category_id', 'user_id');
    }
}