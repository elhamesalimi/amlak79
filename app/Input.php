<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
 public $timestamps = null;
    protected $table = 'fields';
    protected $casts = [
        'options' => 'array',
    ];
    public function types()
    {
        return $this->belongsToMany(Type::class,'field_type','field_id','type_id')->withPivot('category','order');
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function fields()
    {
        return $this->belongsToMany(Input::class,'estate_field','field_id','estate_id')->withPivot('value');

    }

}
