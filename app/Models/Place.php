<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'address',
        'description',
        'weekday_price',
        'weekend_price',
        'tag',
        'category_id',
        'vendor_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [

    ];

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'place_amenities');
    }

    public function specialDays()
    {
        return $this->hasMany(SpecialDay::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class);
    }

    public function placeImages()
    {
        return $this->hasMany(PlaceImage::class);
    }

    public function scopeWithAverageRating($query)
    {
        return $query->selectRaw('places.*, AVG(ratings.rate) as rating')
            ->leftJoin('ratings', 'places.id', '=', 'ratings.place_id')
            ->groupBy('places.id');
    }

    // public function averageRating()
    // {
    //     return $this->ratings()->selectRaw('AVG(rate) as rating')->groupBy('place_id');
    // }
}
