<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GiftCardComment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute( $value ) {
        return (new Carbon($value))->format('F d, Y H:i:s');
    }

    public function getUpdatedAtAttribute( $value ) {
        return (new Carbon($value))->format('F d, Y H:i:s');
    }
}
