<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'start_date',
        'end_date',
        'place_id',
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

}
