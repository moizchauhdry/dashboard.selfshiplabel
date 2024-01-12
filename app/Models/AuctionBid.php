<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionBid extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(User::class,'bidder_id');
    }

    protected $appends = ['customer_name'];

    public function getCustomerNameAttribute()
    {
        return $this->customer->name ?? 'N/A';
    }

}
