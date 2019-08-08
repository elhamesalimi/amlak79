<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    public function estates()
    {
        return $this->belongsToMany(Estate::class,'bug_estate','bug_name','estate_id')->using('App\BugEstate')->withPivot(['meta','created_at'])->orderBy('bug_estate.created_at')->withTimestamps();
    }

    public function getCreatedAtColumn()
    {
//        return jdate($this->attributes('created_at'))->format('Y-m-d');
    }
}
