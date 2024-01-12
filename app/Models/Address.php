<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $appends = ['country_name'];
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function getCountryNameAttribute()
    {
        return ucwords($this->country->name) ?? 'N/A';
    }
}
