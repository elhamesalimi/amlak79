<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\City;
use App\Email;
use App\Estate;
use App\Facility;
use App\Field;
use App\Input;
use App\Notifications\SendSabtEmail;
use App\Notifications\publishedToTelegram;
use App\Owner;
use App\Plan;
use App\Region;
use App\Telephone;
use App\Type;
use App\User;
use Carbon\Carbon;
use Cron\FieldFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;
use Zend\Diactoros\Response;

class EstateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $amlak_email;
    private $email_field;

    public function __construct()
    {
        $this->amlak_email = 'info@amlak79.ir';
        $email_field = new Field();
        $email_field->id = "24";
        $email_field->name = "email";
        $email_field->validation = "nullable|email";
        $this->email_field = $email_field;
    }

    public function getFields(Request $request)
    {
        $type = Type::find($request->type_id);
        $category = ($request->category === 'sell' || $request->category === 'presell') ? 'sell' : 'rent';
        $fields = $type->fields()->wherePivot('category', $category)->orderBy('order', 'asc')->get();

        $facility = $fields->where('name', 'facility')->first();
        $facilities = [];
        if ($facility) {
            $facilities = Facility::all();
        }
        $fields_value = [];
        if ($request->estate_id) {
            $estate = Estate::find($request->estate_id);
            foreach ($estate->fields as $name => $value) {
                if ($value !== null) {
                    if ($name === 'delivery') {
                        $date = Carbon::parse($value);
                        $now = Carbon::now();
                        $fields_value[$name] = $date->lte($now) ? -1 : $date->diffInMonths($now) + 1;
                    } else {
//                        $fields_value[$property->name] = $property->name === 'floor' ? str_replace(['[', ']'], '', $property->pivot->value) : $property->pivot->value;
                        $fields_value[$name] = $value;
                    }
                }
            }
        }
        return view('admin.estate.sabt.partials.fieldLoop', compact('fields', 'facilities', 'fields_value'));
    }

    public function renderRegions()
    {
        $regions = Region::where('city_id', request()->city_id)->pluck('name', 'id')->toArray();
        return $regions;
        return view('admin.estate.sabt.partials.regions', compact('regions'));
    }

    public function index(Request $request)
    {
        $request->session()->put('code', $request->has('code') ? $request->get('code') : ($request->session()->has('code') ? $request->session()->get('code') : ''));
        $request->session()->put('search', $request->has('search') ? $request->get('search') : ($request->session()->has('search') ? $request->session()->get('search') : ''));
        $request->session()->put('field_title', $request->has('field_title') ? $request->get('field_title') : ($request->session()->has('field_title') ? $request->session()->get('field_title') : 'created_at'));
        $request->session()->put('sort', $request->has('sort') ? $request->get('sort') : ($request->session()->has('sort') ? $request->session()->get('sort') : 'desc'));
        $request->session()->put('type', $request->has('type') ? $request->get('type') : ($request->session()->has('type') ? $request->session()->get('type') : 'all'));

        $estates =  Estate::whereHas('owner',function($q)use($request){
            $q->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->session()->get('search') . '%')
                    ->orWhere('users.phone','like','%'. $request->session()->get('search') . '%')
                    ->orWhere('users.name','like','%'. $request->session()->get('search') . '%');
            });
        })->where('id', 'like', '%' . $request->session()->get('code') . '%');
        switch ($request->session()->get('type')) {
            case 'all' :
                $estates->where('status', '!=', 'DRAFT');
                break;
            case 'published' :
                $estates->where('status', 'PUBLISHED');
                break;
            case 'failed' :
                $estates->where('status', 'FAILED');
                break;
            case 'pending' :
                $estates->where('status', 'PENDING');
                break;
            case 'draft' :
                $estates->where('status', 'DRAFT');
                break;
            case 'hidden' :
                $estates->where('status', 'HIDDEN');
                break;
            case 'waiting' :
                $estates->where('status', 'WAITING');
                break;
            case 'removed' :
                $estates->where('status', 'REMOVED');
                break;
        }
        $estates = $estates ->orderBy($request->session()->get('field_title'), $request->session()->get('sort'))
            ->paginate(20);
        if ($request->ajax()) {
            return view('admin.estate.sabt.index', compact('estates'));
        }
        return view('admin.estate.sabt.ajax', compact('estates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($category)
    {
        $cities = City::pluck('name', 'id')->toArray();
        $types = Type::pluck('name', 'id')->toArray();
        $plans = Plan::pluck('name', 'id')->toArray();
        $experts = Admin::where('role', 'expert')->pluck('name', 'id')->toArray();
        return view('admin.estate.sabt.create', compact('cities', 'types', 'plans', 'category', 'experts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        if ($request->filled('floor')) {
            $floor = array_map(function ($value) {
                return intval($value);
            }, $request->floor);
            $request->merge(['floor' => $floor]);
        }
        $rules = array(
            'name' => 'required',
            'phone' => 'required|string|min:11|max:11',
            'email' => 'nullable|email',
            'plan_id' => 'required|numeric|min:1',
            'area' => 'required|numeric|min:5',
            'price' => 'required|numeric|min:0|max:100000000',
            'total_price' => 'required|numeric|min:10000000|max:100000000000',
            'region_id' => 'required|numeric|min:1',
            'type_id' => 'required|numeric|min:1',
            'city_id' => 'required|numeric|min:1',
        );
        $type = Type::find($request->type_id);
        $category = ($request->category === 'sell' || $request->category === 'presell') ? 'sell' : 'rent';
        $fields = $type->fields()->wherePivot('category', $category)->get(['validation', 'name', 'id']);

        $facility = $fields->where('name', 'facility')->first();

        foreach ($fields as $field) {
            if ($field->validation)
                $rules[$field->name] = $field->validation;
        }
        if ($request->cabinet) {
            $rules['type_cabinet'] = ['required'];
        }
        if ($request->exchange) {
            $rules['exchange_with'] = ['required'];
        }
        if ($request->has_loan) {
            $rules['loan_amount'] = ['required'];
        }
        if ($request->presell) {
            $rules['delivery'] = ['required'];
        }
        $total_price_label = $request->category === 'rent' ? 'رهن' : 'قیمت کل';
        $price_label = $request->category === 'rent' ? 'اجاره ' : 'قیمت ';
        $messages = [
            'total_price.required' => "$total_price_label الزامی است.",
            'price.required' => "$price_label الزامی است.",
            'total_price.max' => "$total_price_label  بیشتر از 100 میلیارد نباشد.",
            'total_price.min' => "$total_price_label  کمتر از 10 میلیون نباشد.",
            'price.max' => "$price_label  بیشتر از 100 میلیون نباشد.",
            'price.min' => "$price_label کمتر از 0 نباشد.",
            'total_price.numeric' => "$total_price_label باید عدد باشد.",
            'price.numeric' => "$total_price_label باید عدد باشد.",
        ];
        $request->validate($rules, $messages);
        $owner = User::wherePhone($request->phone)->first();
        if (!$owner) {
            $owner = new User();
            $owner->name = $request->name;
            $owner->phone = $request->phone;

            $owner->password = Hash::make(1234);
            $owner->save();
        }
        $email = [];
        if ($request->filled('email')) {
            foreach ($owner->emails as $item) {
                if ($item->email === $request->email)
                    $email = $item;
            }
            if (empty($email)) {
                $email = new Email();
                $email->email = $request->email;
                $email->user_id = $owner->id;
                $email->save();
            }
        }

//        $superAdminEmail = Email::where('email', $request->email)->first();
        if ($request->category === 'rent') {
            $title = 'اجاره ';
        } elseif (isset($request->delivery)) {
            $title = 'پیش فروش ';
        } else {
            $title = 'فروش ';
        }

        $type_name = Type::find($request->type_id)->name;
        $title .= $type_name;
        $title .= ' به متراژ ';
        $title .= $request->area;
        $region_name = Region::find($request->region_id)->name;
        $title .= ' ' . $region_name;
        //convert floor to array
//        $request->filled('floor') && $request->floor = array_map('intval', explode(',', str_replace([',', 'و'], ',', $request->floor)));
        // set delivery time
        if ($request->filled('delivery')) {
            $request->delivery = Carbon::now()->addMonth($request->delivery);
            $request->age = 0;
            $request->housting = 'تخلیه';
        };
        $estate = new Estate();
//اصلاح id ادمین بعد از کامل شدن لاگین
        $estate->user_id = Auth::guard('admin-api')->check() ? Auth::guard('admin-api')->user()->id : $owner->id;
        $estate->owner_id = $owner->id;
        $estate->type_id = $request->type_id;
        $estate->city_id = $request->city_id;
        $estate->region_id = $request->region_id;
        $estate->plan_id = $request->plan_id;
        $estate->title = $title;
        $estate->price = $request->price;
        $estate->total_price = $request->total_price;
        $estate->area = $request->area;
        $estate->category = isset($request->delivery) ? 'presell' : $request->category;
        $estate->status = $request->status;
        $estate->advertiser = $owner->role;
        $estate->offer = $request->offer;
        $estate->reference = ($request->reference === 'amlak79' || $request->reference === 'expert') ? true : false;
        $res = $estate->save();
        $estate_code = self::quickRandom($estate->id);
        $estate->code = $estate_code;
        //set image upload
        // مدیریت آگهی برای سوپر ادمین ایمیل شود.
        $superAdminEmail = $this->amlak_email;
        $owner->email = $superAdminEmail;
//        $estate->email_type = $superAdminEmail ? 'amlak' : 'landlord';
//        $owner->notify(new SendSabtEmail($estate));

//        sms ارسال نشود.
        if (!$res) {
            return response()->json(['message' => 'خطایی در ثبت ملک به وجود آمده است.'], 400);
        }

        $estate->experts()->attach($request->experts);
        $sync_fields = [];
        foreach ($fields as $field) {
            if (isset($request->{$field->name})) {
                $options[$field->name] = $field->name === 'exchange' ? true : $request->{$field->name};
                $sync_fields[$field->id] = ['value' => $field->name === 'floor' ? json_encode($request->floor) : $request->{$field->name}];
            }
        }
        $request->has('cabinet_type') && $sync_fields[20] = ['value' => $request->type_cabinet];
        $estate->properties()->sync($sync_fields);

        $checkedFacility = [];
        $checkedOptions = [];
        if ($facility) {
            $facilities = Facility::all();
            foreach ($facilities as $facility) {
                if ($request->{$facility->slug}) {
                    array_push($checkedFacility, $facility->id);
                    array_push($checkedOptions, $facility['slug']);
                }
            }
            if (empty(!$checkedFacility)) {
                $estate->facilities()->sync($checkedFacility);
                $options['facilities'] = $checkedOptions;
            }
        }
        $estate->fields = $options;
        $estate->save();

        if (isset($request->images)) {
            return redirect()->action('ImageController@setEstatePhotos', ['estate_id' => $estate->id, 'imagesId' => $request->images]);
        }
        return response('estate create successfully', 200);
    }

    public function getMyEstate()
    {
        $user = Auth::guard('api')->user();
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        $estate = Estate::with(['facilities', 'properties'])->find($id);
////        $date = \Morilog\Jalali\Jalalian::forge($estate->created_at)->ago();
////        $estate->created_at = $date;
////        return $estate;
//
//        if (!$estate) {
//            return response('estate not found', 404);
//        }
//        $fields = array();
//        foreach ($estate->properties as $property) {
//            if ($property->pivot->value) {
//                $fields[$property->name] = $property->pivot->value;
//            }
//        }
//        $estate->fields = $fields;
//
//        $area = $estate->area;
//        $total_price = $estate->total_price;
//        $min_total_price = $total_price - (10 * $total_price / 100);
//        $max_total_price = $total_price + (15 * $total_price / 100);
//
//        $min_area = $area - (15 * $area / 100);
//        $max_area = $area + (15 * $area / 100);
//        $similarEstates = Estate::where('id', '<>', $estate->id)->where('region_id', $estate->region_id)->whereBetween('area', array($min_area, $max_area))->whereBetween('total_price', array($min_total_price, $max_total_price));
//        if ($estate->category === 'rent') {
//            $price = $estate->price;
//            $min_price = $price - (15 * $price / 100);
//            $max_price = $price + (15 * $price / 100);
//            $similarEstates = $similarEstates->where('price', array($min_price, $max_price));
//        }
//
////        return response()->json(['types'=>$types , 'regions'=>$regions , 'estate'=>$estate , 'similarEstates' => $similarEstates->take(6)->get()]);
//        return response()->json(['estate' => $estate, 'fields' => $fields, 'similarEstates' => $similarEstates->take(6)->get()]);
//
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request, $id)
    {
        $estate = Estate::with(['owner', 'facilities', 'experts', 'images'])->findOrFail($id);
        if ($estate->email_id) {
            $email = Email::find($estate->email_id);
            if ($email)
                $estate->owner->email = $email->email;
        }
        $cities = City::pluck('name', 'id')->toArray();
        $types = Type::all();
        $plans = Plan::pluck('name', 'id')->toArray();
        $regions = Region::where('city_id', $estate->city_id)->pluck('name', 'id')->toArray();
        $experts = Admin::where('role', 'expert')->pluck('name', 'id')->toArray();
        $category = ($estate->category === 'sell' || $estate->category === 'presell') ? 'sell' : 'rent';
        $fields = $types->find($estate->type_id)->fields()->wherePivot('category', $category)->orderBy('order', 'asc')->get();
        $facility = $fields->where('name', 'facility')->first();
        $facilities = [];
        if ($facility) {
            $facilities = Facility::all();
        }
        $facilities_value = isset($estate->fields['facilities']) ? $estate->fields['facilities'] : [];
        $types = $types->pluck('name', 'id')->toArray();
        $fields_value = array();
        foreach ($estate->fields as $name => $value) {
            if ($value !== null) {
                if ($name === 'delivery') {
                    $date = Carbon::parse($value['date']);
                    $now = Carbon::now();
                    $fields_value[$name] = $date->lte($now) ? -1 : $date->diffInMonths($now) + 1;
                } else {
//                    $fields_value[$name] = $name === 'floor' ? str_replace(['[', ']'], '', $value) : $value;
                    $fields_value[$name] = $value;
                }
            }
        }
        return view('admin.estate.sabt.edit', compact('estate', 'facilities', 'fields', 'cities', 'types', 'plans', 'experts', 'regions', 'fields_value', 'facilities_value'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    function update(Request $request, $id)
    {
        if ($request->filled('floor')) {
            $floor = array_map(function ($value) {
                return intval($value);
            }, $request->floor);
            $request->merge(['floor' => $floor]);
        }
        if (!Auth::guard('admin')->check()) {
            return redirect()->action('Admin\Auth\LoginController@showLoginForm');
        }
        $main_rule = array(
            'name' => 'required',
            'phone' => 'required|string|min:11|max:11',
            'email' => 'nullable|email',
            'plan_id' => 'required|numeric|min:1',
            'area' => 'required|numeric|min:5',
            'price' => 'required|numeric|min:0|max:100000000',
            'total_price' => 'required|numeric|min:10000000|max:100000000000',
            'region_id' => 'required|numeric|min:1',
            'type_id' => 'required|numeric|min:1',
            'city_id' => 'required|numeric|min:1',
            'created_at' => 'required',
//            'day' => 'required|integer|between:1,31',
//            'month' => 'required|integer|between:1,12',
//            'year' => 'required|integer|between:1300,1400',
//            'minute' => 'required|integer|between:0,59',
//            'hour' => 'required|integer|between:0,23',
        );
        $type = Type::find($request->type_id);
        $category = ($request->category === 'sell' || $request->category === 'presell') ? 'sell' : 'rent';
        $fields = $type->fields()->wherePivot('category', $category)->get(['validation', 'name', 'id']);
        $email_field = $this->email_field;
        $email_field->value = $request->email;
        $fields[] = $email_field;
        $fields [] = $this->email_field;
        $facility = $fields->where('name', 'facility')->first();
        $field_rule = array();
        foreach ($fields as $field) {
            if ($field->validation)
                $field_rule[$field->name] = $field->validation;
        }
        $rule = array_merge($main_rule, $field_rule);
        $total_price_label = $request->category === 'rent' ? 'رهن' : 'قیمت کل';
        $price_label = $request->category === 'rent' ? 'اجاره ' : 'قیمت ';
        $messages = [
            'total_price.required' => "$total_price_label الزامی است.",
            'price.required' => "$price_label الزامی است.",
            'total_price.max' => "$total_price_label  بیشتر از 100 میلیارد نباشد.",
            'total_price.min' => "$total_price_label  کمتر از 10 میلیون نباشد.",
            'price.max' => "$price_label  بیشتر از 100 میلیون نباشد.",
            'price.min' => "$price_label کمتر از 0 نباشد.",
            'total_price.numeric' => "$total_price_label باید عدد باشد.",
            'price.numeric' => "$total_price_label باید عدد باشد.",
        ];
        $request->validate($rule, $messages);
        $owner = User::updateOrCreate(['phone' => $request->phone], ['name' => $request->name, 'password' => Hash::make(1234)]);

        //create timeStamp
        $unix_lenght = strlen($request->created_at);
        $unix_timestamp = $unix_lenght > 10 ? substr($request->created_at, 0, -($unix_lenght - 10)) : $request->created_at;
        $unix_timestamp = Carbon::createFromTimestamp((int)$unix_timestamp);
        //

        $estate = Estate::findOrFail($id);
        $email = [];

        if ($request->filled('email') && str_replace(' ', '', $request->email) !== $this->amlak_email) {
            foreach ($owner->emails as $item) {
                if ($item->email === $request->email)
                    $email = $item;
            }
            if (empty($email)) {
                $email = new Email();
                $email->email = $request->email;
                $email->user_id = $owner->id;
                $email->save();
            }
        }
        $email = Email::where('email', $request->email)->first();
        //convert floor to array
//        $request->filled('floor') && $request->floor = array_map('intval', explode(',', str_replace([',', 'و'], ',', $request->floor)));
        // set delivery time
        if ($request->filled('delivery')) {
            $request->delivery = Carbon::now()->addMonth($request->delivery);
            $request->age = 0;
            $request->housting = 'تخلیه';
            $request->category = 'presell';
        } elseif ($request->category === 'presell') {
            $request->category = 'sell';
        }
        //اصلاح id ادمین بعد از کامل شدن لاگین
        $estate->user_id = Auth::guard('admin-api')->check() ? Auth::guard('admin-api')->user()->id : $owner->id;
        $estate->owner_id = $owner->id;
//        $estate->email_id = $email->id;
        $estate->type_id = $request->type_id;
        $estate->city_id = $request->city_id;
        $estate->region_id = $request->region_id;
        $estate->plan_id = $request->plan_id;
        $estate->title = $request->category === 'presell' ? strpos($request->title, 'پیش فروش') === false ? str_replace('فروش', 'پیش فروش', $request->title) : $request->title : str_replace('پیش فروش', 'فروش', $request->title);
        $estate->price = $request->price;
        $estate->total_price = $request->total_price;
        $estate->area = $request->area;
        $estate->category = $request->category;
        $estate->status = $request->status;
//        $estate->advertiser = Auth::check() ? Auth::user()->role :  'owner';
        $estate->listen = $request->listen ? true : false;
        $estate->offer = $request->offer;
//        $estate->created_at = (new Jalalian($request->year, $request->month, $request->day, $request->hour, $request->minute, 0))->toCarbon()->toDateTimeString();
        $estate->created_at = $unix_timestamp;
        $estate->reference = ($request->reference === 'amlak79' || $request->reference === 'expert') ? true : false;
        $estate->experts()->sync($request->experts);
        $sync_fields = [];
        $options = $estate->feilds;
        foreach ($fields as $field) {
            if (isset($request->{$field->name})) {
                $sync_fields[$field->id] = ['value' => $field->name === 'floor' ? json_encode($request->{$field->name}) : $request->{$field->name}];
                $options[$field->name] = $field->name === 'exchange' ? true : $request->{$field->name};
            }
        }
        $estate->properties()->sync($sync_fields);
        $checkedFacility = [];
        $checkedOptions = [];
        if ($facility) {
            $facilities = Facility::all();
            foreach ($facilities as $facility) {
                if ($request->{$facility->slug}) {
                    array_push($checkedFacility, $facility->id);
                    array_push($checkedOptions, $facility->slug);
                }
            }
            if (empty(!$checkedFacility)) {
                $estate->facilities()->detach();
                $estate->facilities()->sync($checkedFacility);
                $options['facilities'] = $checkedOptions;
            }
        }
        $estate->fields = $options;
        $res = $estate->save();

        if (!$res) {
            return response()->json(['message' => 'خطایی در ثبت ملک به وجود آمده است.'], 400);
        }
        if ($estate->status === 'PUBLISHED' && $request->listen) {
            $estate->addToSimilarDarkhasts();
        }
        if ($estate->status === 'PUBLISHED' && $request->telegram) {
            $estate->notify(new publishedToTelegram());
        }

        if (isset($request->images)) {
            return redirect()->action('ImageController@setEstatePhotos', ['estate_id' => $estate->id, 'imagesId' => $request->images]);
        }

        return redirect()->action('Admin\EstateController@index')->with(['message' => 'ملک با موقیت ویرایش شد.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        $estate = Estate::findOrFail($id);
        if ($estate->status === 'DRAFT') {
            $estate->delete();
        } else {
            $estate->status = 'DRAFT';
            $estate->save();
        }

        return redirect()->action('Admin\EstateController@index');
    }

    public function emptyTrash()
    {
        Estate::where('status', 'draft')->delete();
        return redirect()->action('Admin\EstateController@index');
    }

    static function quickRandom($id)
    {
        $length = 16;
        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length) . $id;
    }

    public function changeEstateStatus()
    {
        $selectedIds = request()->checkedValue;
        $status = request()->status;
        if ($status === 'DELETE') {
            Estate::destroy($selectedIds);
        } else {
            Estate::whereIn('id', $selectedIds)->update(['status' => $status]);
        }
        return redirect()->action('Admin\EstateController@index');

    }
}
