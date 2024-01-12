<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public static function getByName($name)
    {
        $data =  self::where('name', '=', $name)->first();

        if (is_object($data)) {
            return $data->value;
        }

        return false;
    }
}
