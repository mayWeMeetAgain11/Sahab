<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'description',
        'duration',
        'price',
        'tag',
        'featured',
        'max_capacity',
        'available',
        'bookable',
        'category_id',
        'vendor_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [

    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function availableTimes()
    {
        return $this->hasMany(AvailableTime::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function serviceImages()
    {
        return $this->hasMany(ServiceImage::class);
    }

    public function averageRating()
    {
        return $this->ratings()->selectRaw('AVG(rate) as rating')->groupBy('service_id');
    }

}
