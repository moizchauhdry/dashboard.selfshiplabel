<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, Notifiable, HasApiTokens;
    protected $guarded = [];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function address()
    {
        return $this->hasMany(Address::class, 'user_id');
    }
}
