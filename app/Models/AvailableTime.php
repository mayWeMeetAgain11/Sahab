<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'starting_date',
        'ending_date',
        'service_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [

    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

}
