<?php

namespace App;

use App\Http\Controllers\Admin\EstateController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Estate extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $casts = [
        'floor' => 'array',
        'fields' => 'array',
        'fields.floor' => 'array'
    ];
    protected $guarded = ['id'];
    const VALIDATION_RULES = [
        'name' => 'required',
        'phone' => 'required|max:11',
        'telephone' => 'nullable|max:11',
        'email' => 'nullable|email',
        'plan_id' => 'required|numeric',
        'area' => 'required|numeric|min:5',
        'region_id' => 'required|numeric|min:1',
        'type_id' => 'required|numeric|min:1',
        'city_id' => 'required|numeric|min:1',
    ];

    public function avatar()
    {
        return $this->images()->where('avatar',true)->uri;
    }
//    public function setFieldsAttribute($value)
//    {
////        if (isset($value['floor'])) {
////            return response($value,404);
////            $fields = !empty($this->attributes['fields'])?$this->attributes['fields']: [];
////            array_push($fields,['floor'=>json_encode($value['floor'])]);
////            $this->attributes['fields'] = $fields;
////        }
//    }


    public function bugs()
    {
        return $this->belongsToMany(Bug::class, 'bug_estate', 'estate_id', 'bug_name')->using('App\BugEstate')->withPivot('meta')->withTimestamps();
    }

    public function experts()
    {
        return $this->belongsToMany(Admin::class, 'estate_partner', 'estate_id', 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function properties()
    {
        return $this->belongsToMany(Input::class, 'estate_field', 'estate_id', 'field_id')->withPivot('value');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }
//
//    public function setFloorAttribute($value)
//    {
//        return json_encode($value);
//    }


    public function addToSimilarDarkhasts()
    {
        $estate = $this;
        $category = $estate->category;
        $sell = $category === 'sell' ? true : false;
        $similar_darkhasts = Darkhast::where('category', $category)->where('type_id', $estate->type_id)
            ->whereJsonContains('region_ids', (int)$estate->region_id)
            ->when($sell, function ($q) use ($estate) {
                $q->where('max_price', '>=', $estate->total_price)
                    ->where(function ($qu) use ($estate) {
                        return $qu->whereNull('min_price')->orWhere('min_price', '<=', $estate->total_price);
                    });
                $q->where('min_area', '<=', $estate->area)->where(function ($qe) use ($estate) {
                    return $qe->whereNull('max_area')->orWhere('max_area', '>=', $estate->area);
                });
                $q->when(isset($estate->fields['room']), function ($q) use ($estate) {
                    return $q->where(function ($qe) use ($estate) {
                        return $qe->whereNull('room')->orWhere('room', '<=', $estate->fields['room']);
                    });
                });
                $q->when(isset($estate->fields['facilities']) && !in_array('elevator', $estate->fields['facilities']), function ($q) {
                    $q->where('elevator', 0);
                    return $q;
                });
                $q->when(isset($estate->fields['facilities']) && !in_array('parking', $estate->fields['facilities']), function ($q) {
                    $q->where('parking', 0);
                    return $q;
                });
                return $q;
            }, function ($q) use ($estate) {
                return $q->where('max_mortgage', '>=', $estate->total_price)->where(function ($query) use ($estate) {
                    return $query->whereNull('min_mortgage')->orWhere('min_mortgage', '<=', $estate->total_price);
                })
                    ->where('max_rent', '>=', $estate->price)->where(function ($query) use ($estate) {
                        return $query->whereNull('min_rent')->orWhere('min_rent', '<=', $estate->price);
                    })
                    ->where('min_area', '<=', $estate->area)->where(function ($query) use ($estate) {
                        return $query->WhereNull('max_area')->orWhere('max_area', '>=', $estate->area);
                    })
                    ->when(isset($estate->fields['room']), function ($q) use ($estate) {
                        return $q->where(function ($qe) use ($estate) {
                            return $qe->whereNull('room')->orWhere('room', '<=', $estate->fields['room']);
                        });
                    })
                    ->when(isset($estate->fields['facilities']) && !in_array('elevator', $estate->fields['facilities']), function ($q) {
                        $q->where('elevator', 0);
                        return $q;
                    })
                    ->when(isset($estate->fields['facilities']) && !in_array('parking', $estate->fields['facilities']), function ($q) {
                        $q->where('parking', 0);
                        return $q;
                    });
            })->get();

        foreach ($similar_darkhasts as $darkhast) {
            $darkhast->addSimilarEstate($estate->id);
        }
    }

    public function getDetails()
    {
        $pricefunc = function ($price) {
            if ($price === 0)
                return 'مجانی';
            $num_len = strlen($price);
            if ($num_len > 9) {
                $content = (float)$price / 1000000000 . ' میلیارد';
            } else {
                $content = (float)$price / 1000000 . ' میلیون';
            };
            return $content;
        };
        $office_num = '02833682408';
        $fields = $this->fields;
        $facilities = array_key_exists('facilities', $fields) ? $fields['facilities'] : null;
        $content = "*" . $this->title . "* \n";
        $content .= 'کد : ' . $this->id . "\n";
        $price = $pricefunc($this->total_price);
        $content .= ($this->category === 'rent' ? 'رهن : ' : 'قیمت کل : ') . $price . "\n";
        $content .= $this->category === 'rent' ? 'اجاره : ' . ($this->price === 0 ? "مجانی \n" : $this->price . " تومان\n") : '';
        $func = function ($floor) {
            switch ($floor) {
                case 0:
                    return 'همکف';
                    break;
                case -1:
                    return 'زیرزمین';
                    break;
                default:
                    return $floor;
            }
        };
        $facilityTrans = function ($facility) {
            switch ($facility) {
                case 'elevator':
                    return 'آسانسور';
                    break;
                case 'parking':
                    return 'پارکینگ';
                    break;
            }
        };
        $content .= array_key_exists('unit', $fields) ? $fields['unit'] . ' واحد' . "\n" : '';
        $content .= array_key_exists('floor', $fields) ? ' طبقه ' . implode(' و ', array_map($func, $fields['floor'])) . "\n" : '';
        $content .= array_key_exists('age', $fields) ? ($fields['age'] === "0" ? 'نوساز ' . "\n" : $fields['age'] . ' ساله ' . "\n") : '';
        $content .= array_key_exists('tarakom', $fields) ? 'تراکم : ' . $fields['tarakom'] . "\n" : '';
        $content .= array_key_exists('bahr', $fields) ? 'عرض زمین : ' . $fields['bahr'] . "\n" : '';
        $content .= $this->plan ? $this->plan->name . "\n" : '';
        if ($facilities) {
            $org_facilities = Facility::pluck('name', 'slug')->toArray();
            foreach ($facilities as $facility) {
                $content .= $facility === 'cabinet' ? "کابینت " . $fields['type_cabinet'] . "\n" : $org_facilities[$facility] . "\n";
            }
        }
//        $content .= $facilities && (in_array('elevator', $facilities) || in_array('parking', $facilities)) ? implode(' و ', array_map($facilityTrans, array_filter($facilities, function ($facility) {
//            if ($facility === 'elevator' || $facility === 'parking') {
//                return true;
//            }
//        }))) : '';
//        $content .= "\n";
        $contact = \App\Http\Controllers\Api\EstateController::getEstateContact($this);
        $title = str_replace('تماس ', '', $contact['title']);
        $phones = implode(',', $contact['phones']);
        $content .= "\n";
        $content .= " $title :  $phones \n";
        if ($title !== 'املاک 79')
            $content .= " *املاک 79 : *$office_num \n";
        return $content;
    }

}
