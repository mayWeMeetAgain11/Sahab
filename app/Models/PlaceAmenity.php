<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceAmenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'place_id',
        'amenity_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [

    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

}
