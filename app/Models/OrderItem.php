<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function originCountry()
    {
        return $this->belongsTo(Country::class, 'origin_country');
    }
}
