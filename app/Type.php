<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $timestamps = null;

    public function fields()
    {
        return $this->belongsToMany(Input::class,'field_type','type_id','field_id')->withPivot('category','order');
    }

}
