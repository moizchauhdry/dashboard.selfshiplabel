<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Auction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['main_image','category_name','latest_bid','is_bidder_selected'];

    /**
     * Get all of the images for the Auction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function images()
    {
        return $this->hasMany(AuctionImage::class);
    }

    public function category()
    {
        return $this->belongsTo(AuctionCategory::class);
    }

    public function getMainImageAttribute(){
        
        return $this->images()->where('featured',1)->first()->image ?? '';

    }

    public function bids(): HasMany
    {
        return $this->hasMany(AuctionBid::class,'auction_id')->orderByDesc('amount');
    }

    public function getCategoryNameAttribute(){
        
        return $this->category->name ?? '';
    }

    public function getLatestBidAttribute()
    {
        return $this->bids()->orderByDesc('amount')->first();
    }

    public function getIsBidderSelectedAttribute()
    {
        return $this->bids()->where('is_selected',1)->count();
    }

}
