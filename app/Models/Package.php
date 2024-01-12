<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['service_charges'];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function packageHandler()
    {
        return $this->belongsTo(User::class, 'package_handler_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_book_id');
    }

    public function shipFrom()
    {
        return $this->belongsTo(Address::class, 'ship_from');
    }

    public function shipTo()
    {
        return $this->belongsTo(Address::class, 'ship_to');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function packageItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function boxes()
    {
        return $this->hasMany(PackageBox::class);
    }

    public function images()
    {
        return $this->hasManyThrough('App\Models\OrderImage', 'App\Models\Order');
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d h:i:s', strtotime($value));
    }

    public function setShippingAddressAttribute()
    {
        $address = $this->address;
        if ($address == NULL) {
            return '- -';
        }
        return $address->address;
    }

    public function getShippingAddressAttribute()
    {
        $address = $this->address;
        if ($address == NULL) {
            return '- -';
        }
        return $address->address . ', ' . $address->city . ', ' . ucfirst(strtolower($address->country->name));
    }

    public function getServiceChargesAttribute()
    {
        $serviceCharges = $this->serviceRequests;
        \Log::info($serviceCharges);
        $sum = 0;
        foreach ($serviceCharges as $service) {
            $sum += $service->price;
        }
        $mailOutFee = SiteSetting::where('name', 'mailout_fee')->orderBy('id', 'desc')->first()->value;
        $sum += $mailOutFee;
        return $sum;
    }

    public function child_packages()
    {
        return $this->hasMany(Package::class, 'package_handler_id', 'id');
    }

    public function coupon()
    {
        return $this->hasOne(CouponPackage::class, 'package_id', 'id');
    }

    public function scopeCart($query)
    {
        return $query->where('project_id', 2)->where('customer_id', auth()->id())->orderBy('id', 'desc')->where('cart', 1);
    }
}
