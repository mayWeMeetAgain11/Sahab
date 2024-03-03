<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'starting_date',
        'ending_date',
        'payment_method',
        'address', // known what about it
        'transaction_id',
        'invoice_reference',
        'status',
        'place_id',
        'service_id',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function promoCode()
    {
        return $this->hasMany(PromoCode::class);
    }

}
