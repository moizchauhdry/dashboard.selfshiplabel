<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuctionCategory extends Model
{
    use HasFactory;

    public function auctions(): HasMany
    {
        return $this->hasMany(Auction::class);
    }

    protected $appends = ['auctions_count'];

    public function getAuctionsCountAttribute()
    {
        return $this->auctions()->where('ending_at','>',Carbon::now())->count() ?? 0;
    }
}
