<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Darkhast extends Model
{

    public $timestamps = false;
// ...
    // boot

//    protected $fillable = ['similar_estate_ids','region_ids','meta'];
    protected $casts = [
        'id' => 'int',
        'region_ids' => 'array',
        'similar_estate_ids' => 'array'
    ];

    public function getCreatedAtAttribute($value)
    {
        return jdate($value)->format('Y-m-d');
    }

    public function similarEstates()
    {
        return $this->belongsToMany(Estate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addSimilarEstate($estate_id)
    {

        $similar_estate_ids = [];
        if ($this->similar_estate_ids) {
            $similar_estate_ids = $this->similar_estate_ids;
        }
        if (!in_array($estate_id, $similar_estate_ids)) {
            array_push($similar_estate_ids, $estate_id);
            $this->similar_estate_ids = $similar_estate_ids;
            $this->updated_at = Carbon::now();
            $this->save();
        }

    }

    public function removeSimilarEstate($estate_id)
    {
        $similar_estate_ids = [];
        if ($this->similar_estate_ids) {
            $similar_estate_ids = $this->similar_estate_ids;
        }
        if (($key = array_search($estate_id, $similar_estate_ids)) !== false) {
            unset($similar_estate_ids[$key]);
        }
        $this->similar_estate_ids = empty($similar_estate_ids) ? null : (array) $similar_estate_ids;

        $this->save();
    }
}
