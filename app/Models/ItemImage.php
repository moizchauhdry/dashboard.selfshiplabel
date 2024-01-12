<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function item(){
        return $this->belongsTo(OrderItem::class);
    }
    
}
