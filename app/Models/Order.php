<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory, Notifiable;
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(OrderImage::class);
    }

    public function displayImage()
    {
        return $this->hasOne(OrderImage::class)->where('display', 1);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        //return $this->customer->email;

        // Return email address and name...
        return [$this->customer->email => $this->customer->name];
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d h:i:s', strtotime($value));
    }
}
