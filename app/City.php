<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = null;
    public function estates()
    {
        return $this->hasMany(Estate::class);
    }

    public function regions()
    {
        return $this->hasMany(Region::class)->orderBy('arrange');
    }

}
