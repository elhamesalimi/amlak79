<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\City;
use App\Email;
use App\Estate;
use App\Facility;
use App\Field;
use App\Image;
use App\Input;
use App\Notifications\SendSabtEmail;
use App\Owner;
use App\Plan;
use App\Region;
use App\Telephone;
use App\Type;
use App\User;
use Carbon\Carbon;
use Cron\FieldFactory;
use function GuzzleHttp\Psr7\copy_to_string;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EstateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendSms($phone, $code)
    {
//        $code = " کد تایید املاک 79 ";
//        $code .= rand(1111, 9999);
        $username = "saeid1081";
        $password = '9127816488';
        $from = "+100020400";
        $pattern_code = "1188";
        $to = array($phone);
        $input_data = array("tracking-code" => $code, "name" => "املاک 79");
        $url = "http://37.130.202.188/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
//        $url = "http://37.130.202.188/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . $code . "&pattern_code=$pattern_code";
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handler);

    }

    public function getCities()
    {
        $cities = City::all();
        return response()->json($cities);
    }

    public function getPlans()
    {
        $plans = Plan::all();
        return response()->json($plans);
    }

    public function getFacilities()
    {
        $facilities = Facility::all();
        foreach ($facilities as $facility) {
            $facility['isChecked'] = false;
        }
        return response()->json($facilities);
    }

    public function getRegions($city_id)
    {
        $regions = Region::where('city_id', $city_id)->orderBy('arrange')->get();
        return response()->json($regions);
    }

    public function getAllRegions()
    {
        $regions = Region::orderBy('arrange')->get();
        return response()->json($regions);
    }

    public function getRegionsByCitySlug($city_slug = null)
    {
        if ($city_slug) {
            $city = City::whereSlug($city_slug)->first();
            $regions = Region::with('city')->where('city_id', $city->id)->orderBy('arrange')->get();
        } else {
            $regions = Region::with('city')->orderBy('arrange')->get();
        }
        return response()->json($regions);
    }

    public function getTypes()
    {
        $types = Type::all();
        return response()->json($types);
    }

    public function getFields(Request $request)
    {
        $type = Type::find($request->type_id);
        $category = ($request->category === 'sell' || $request->category === 'presell') ? 'sell' : 'rent';
        $fields = $type->fields()->wherePivot('category', $category)->get();
        return $fields;
    }

    public function index(Request $request)
    {
        $city_slug = $request->has('city') ? $request->city : 'qazvin';
        $city = City::whereSlug($city_slug)->first();
        if (!$city) {
            return response()->json(['message' => 'شهری با این نام وجود ندارد'], 404);
        }
        $estates = Estate::with('images')->where('status', 'PUBLISHED')->where('city_id', $city->id);

        if ($request->has('category') && $request->category)
            $estates = $estates->where('category', $request->category);
        $request->has('type_id') && $estates = $estates->where('type_id', $request->type_id);
        if ($request->has('region_id') && $request->region_id)
            $estates = $estates->whereIn('region_id', $request->region_id);
        if ($request->has('plan_id') && $request->plan_id)
            $estates = $estates->whereIn('plan_id', $request->plan_id);
        $request->has('min_price') && $estates = $estates->where('price', '>=', (int)$request->min_price);
        $request->has('max_price') && $estates = $estates->where('price', '<=', (int)$request->max_price);
        $request->has('min_total_price') && $estates = $estates->where('total_price', '>=', (int)$request->min_total_price);
        $request->has('max_total_price') && $estates = $estates->where('total_price', '<=', (int)$request->max_total_price);
        $request->has('min_area') && $estates = $estates->where('area', '>=', (int)$request->min_area);
        $request->has('max_area') && $estates = $estates->where('area', '<=', (int)$request->max_area);
        if ($request->has('advertiser') && $request->advertiser !== 'all')
            $estates = $request->advertiser === 'admin' ? $estates->where('reference', 1) : $estates->where('advertiser', $request->advertiser)->where('reference', false);
        if ($request->has('photo') && $request->photo === 'true')
            $estates = $estates->whereHas('images');
        if ($request->filled('parking') || $request->filled('exchange') || $request->filled('elevator')) {
            $request->parking === 'true' && $estates = $estates->whereJsonContains('fields_facilities', 'parking');
            $request->elevator === 'true' && $estates = $estates->whereJsonContains('fields_facilities', 'elevator');
            $request->exchange === 'true' && $estates = $estates->whereJsonContains('fields_facilities', 'exchange');
        }
//        if ($request->has('elevator') && ($request->elevator === 'true'))
//            $estates = $estates->whereElevator(1);
//        if ($request->has('parking') && $request->parking === 'true')
//            $estates = $estates->whereParking(1);
//        if ($request->has('exchange') && $request->exchange === 'true')
//            $estates = $estates->whereExchange(1);
        if ($request->filled('room'))
            $estates = (int)$request->room === 5 ? $estates->where('fields_room', '>=', (int)$request->room) : $estates->where('fields_room', (int)$request->room);
        if ($request->has('max_age'))
            $estates = $estates->where('fields_age', '<=', (int)$request->max_age);
        if ($request->filled('tarakom'))
            $estates = (int)$request->tarakom === 6 ? $estates->where('fields_tarakom', '>=', (int)$request->tarakom) : $estates->where('fields_tarakom', (int)$request->tarakom);
        if (($request->filled('from_floor')) || ($request->filled('to_floor'))) {
            $from_floor = isset($request->from_floor) ? $request->from_floor : -1;
            $to_floor = isset($request->to_floor) ? $request->to_floor : 20;
            $floors = [];
            for ($from_floor; $from_floor <= $to_floor; $from_floor++) {
                $floors [] = (int)$from_floor;
            }

            $estates->where(function ($query) use ($floors) {
//                $firstId = array_shift($floors);
//                $query->whereJsonContains('floor', $firstId);
                foreach ($floors as $id) {
                    $query->orWhereJsonContains('fields_floor', $id);
                }
            });

        }
        if ($request->filled('max_unit'))
            $estates = $estates->where('fields_unit', '<=', (int)$request->max_unit);

        if (Auth::guard('admin-api')->check()) {
            $role = Auth::guard('admin-api')->user()->role;
            $employee = array('admin', 'expert', 'super_admin');
            if (in_array($role, $employee)) {
                $estates = $estates->with('experts');
            }
        }
//        https://stackoverflow.com/questions/54534440/sql-filter-table-records-by-array-column-values
        return response()->json($estates->orderBy('created_at', 'desc')->get());

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $total_price_label = $request->category === 'rent' ? 'رهن' : 'قیمت کل';
        $price_label = $request->category === 'rent' ? 'اجاره ' : 'قیمت ';
        $messages = [
            'total_price.required' => "$total_price_label الزامی است.",
            'price.required' => "$price_label الزامی است.",
            'total_price.max' => "$total_price_label  بیشتر از 20 میلیارد نباشد.",
            'total_price.min' => "$total_price_label  کمتر از 10 میلیون نباشد.",
            'price.max' => "$price_label  بیشتر از 100 میلیون نباشد.",
            'price.min' => "$price_label  کمتر از 0 نباشد.",
            'total_price.numeric' => "$total_price_label باید عدد باشد.",
            'price.numeric' => "$total_price_label باید عدد باشد.",
        ];

        $rules = Estate::VALIDATION_RULES;

        $rules['price'] = $request->category === 'sell' ? 'required|numeric|min:0|max:100000000' : 'required|numeric|min:0';
        $rules['total_price'] = $request->category === 'sell' ? 'required|numeric|min:10000000|max:20000000000' : 'required|numeric|min:0';

        $fields = $request->fields;
        foreach ($fields as $key => $value) {
            if ($value['validation'])
                $rules['fields.' . $key . '.value'] = $value['validation'];
        }
        $facilities = $request->facilities;
        if (!empty($facilities)) {
            if ($facilities['cabinet']['isChecked']) {
                $rules['fields.type_cabinet.value'] = 'required';
            }
        }
        $rules['fields.floor.value'] = 'required';
        $request->validate($rules, $messages);

        $owner = User::wherePhone($request->phone)->first();
        if (!$owner) {
            $owner = new User();
            $owner->name = $request->name;
            $owner->phone = $request->phone;
            $owner->password = Hash::make(1234);
            $owner->save();
            if ($request->telephone) {
                $telephone = new Telephone();
                $telephone->user_id = $owner->id;
                $telephone->telephone = $request->telephone;
                $telephone->save();
            }
        }
        $email = [];
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

        $superAdminEmail = null;
        if (Auth::guard('admin-api')->check()) {
            // مدیریت آگهی برای ادمین ارسال شود.
            // برای کاربر sms  ارسال نشود.
            $superAdmin = Admin::whereRole('super_admin')->first();
            $superAdminEmail = $superAdmin->email;
        }
        $title = $request->category === 'rent' ? 'اجاره ' : isset($fields['delivery']['value']) ? 'پیش فروش ' : 'فروش ';
        $type_name = Type::find($request->type_id)->name;
        $title .= $type_name;
        $title .= ' به متراژ ';
        $title .= $request->area;
        $title .= ' ';
        $region_name = Region::find($request->region_id)->name;
        $title .= $region_name;
        $advertiser = Auth::guard('admin-api')->check() ? Auth::guard('admin-api')->user() : $owner;

        $estate = new Estate();
        $estate->user_id = $advertiser->id;
        $estate->owner_id = $owner->id;
        $estate->email_id = $superAdminEmail ? 10 : $email->id;
        $estate->type_id = $request->type_id;
        $estate->city_id = $request->city_id;
        $estate->region_id = $request->region_id;
        $estate->plan_id = $request->plan_id;
        $estate->price = $request->price;
        $estate->total_price = $request->total_price;
        $fields['floor']['value'] = isset($fields['floor']['value']) ? gettype($fields['floor']['value']) === 'array' ? $fields['floor']['value'] : array_map('intval', explode(',', str_replace([',', 'و'], ',', $fields['floor']['value']))) : null;
//        isset($fields['room']['value']) && $estate->room = $fields['room']['value'];
//        isset($fields['floor']['value']) && $estate->floor = $fields['floor']['value'];
//        isset($fields['tarakom']['value']) && $estate->tarakom = $fields['tarakom']['value'];
//        isset($fields['unit']['value']) && $estate->unit = $fields['unit']['value'];
//        isset($fields['age']['value']) && $estate->age = $fields['age']['value'];
//        isset($fields['exchange']['value']) && $estate->exchange = $fields['exchange']['value'];
        $estate->area = $request->area;
        $estate->title = $title;
        $estate->category = isset($fields['presell']['value']) ? 'presell' : $request->category;
        $estate->advertiser = $request->owner;
        $status = (Auth::guard('admin-api')->check() || Auth::guard('api')->check()) && ((Auth::guard('api')->user() === $owner)) ? 'PENDING' : 'FAILED';
        $estate->status = $status;
//        if (!empty($facilities)) {
//            if ($facilities['parking']['isChecked']) $estate->parking = true;
//            if ($facilities['elevator']['isChecked']) $estate->elevator = true;
//        }
        $res = $estate->save();
        if (!$res) {
            return response()->json(['message' => 'خطایی در ثبت ملک به وجود آمده است.'], 400);
        }
        $estate_code = self::quickRandom($estate->id);
        $estate->code = $estate_code;

        $sync_fields = [];
        $options = [];
        foreach ($fields as $field) {
            if (isset($field['value'])) {
                $value = $field['value'];
                $options[$field['name']] = $value;
                if ($field['name'] === 'floor') {
                    $value = json_encode($field['value']);
                };
                $sync_fields[$field['id']] = ['value' => $value];
            }
        }
        if (isset($fields['delivery']['value'])) {
            $delivery = Carbon::now()->addMonth($fields['delivery']['value']);
            $options['delivery'] = $delivery;
            $options['age'] = 0;
            $options['housting'] = 'تخلیه';
            $sync_fields[$fields['age']['id']] = ['value' => 0];
            $sync_fields[$fields['housting']['id']] = ['value' => 'تخلیه'];
            $sync_fields[$fields['delivery']['id']] = ['value' => $delivery];
        }
        isset($fields['delivery']['value']) && $sync_fields[$fields['housting']['id']] = ['value' => 'تخلیه'];
        $estate->properties()->sync($sync_fields);

        $checkedFacility = [];
        $checkedOptions = [];

        if (!empty($request->facilities)) {
            foreach ($facilities as $facility) {
                if ($facility['isChecked']) {
                    array_push($checkedFacility, $facility['id']);
                    array_push($checkedOptions, $facility['slug']);
                }
            }
            if (!empty($checkedFacility)) {
                $estate->facilities()->attach($checkedFacility);
                $options['facilities'] = $checkedOptions;
            }
        }
        $estate->fields = $options;
        $estate->save();
//        send email
//         ایمیل مدیریت آگهی برای estate->email_id ارسال شود.
        $email = Email::find($estate->email_id)->email;
        $owner->email = $email;
        $estate->email_type = $superAdminEmail ? 'amlak' : 'landlord';
        $owner->notify((new SendSabtEmail($estate))->delay(1));

        return response()->json(['code' => $estate->code, 'id' => $estate->id], 200);
    }

    public function bookmarkEstates()
    {
        $estate_ids = json_decode(request()->estateIds, true);
        $estates = Estate::whereIn('id', $estate_ids)->where('status', 'PUBLISHED')->get();
        if (empty($estates)) {
            return response('not exist estates', 404);
        }
        return response($estates, 200);
    }

    public function getMyEstate()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function type_id()
    {
        // set type Id for melkhatye similar

    }


    public function show($id)
    {
        if (Auth::guard('admin-api')->check() && (Auth::guard('admin-api')->user()->role === 'admin' || Auth::guard('admin-api')->user()->role === 'super_admin')) {
            $estate = Estate::with(['facilities', 'properties', 'experts', 'images', 'plan', 'owner'])->find($id);

        } else {
            $estate = Estate::with(['facilities', 'properties', 'experts', 'images', 'plan', 'owner'])->where('status', 'PUBLISHED')->find($id);
        }
        if (!$estate) {
            return response('estate not found', 404);
        }
        $estate->date = jdate($estate->created_at)->ago();
        $delivery = isset($estate->fields['delivery']);
        if ($delivery) {
            $estate->delivery = jDate($estate->fields['delivery']['date'])->format('%B %Y');
        }
        $facilities = Facility::pluck('name', 'slug')->toArray();

//        $fields = array();
//        foreach ($estate->properties as $property) {
//            if ($property->pivot->value) {
//                $fields[$property->name] = $property->name === 'floor' ? json_decode($property->pivot->value) : $property->pivot->value;
//            }
//        }
//        $estate->fields = $fields;
        $estate->increment('view_count');

        $area = $estate->area;
        $total_price = $estate->total_price;
        $min_total_price = $total_price - (10 * $total_price / 100);
        $max_total_price = $total_price + (10 * $total_price / 100);

        $min_area = $area - (10 * $area / 100);
        $max_area = $area + (10 * $area / 100);
        $similarEstates = Estate::where('status', 'PUBLISHED')->where('category', $estate->category)
            ->where('id', '<>', $estate->id)->where('type_id', $estate->type_id)->where('city_id', $estate->city_id)
            ->whereBetween('area', array($min_area, $max_area))->whereBetween('total_price', array($min_total_price, $max_total_price));
        if ($estate->category === 'rent') {
            $price = $estate->price;
            $min_price = $price - (10 * $price / 100);
            $max_price = $price + (10 * $price / 100);
            $similarEstates = $similarEstates->whereBetween('price', array($min_price, $max_price));
        }
//        return response()->json(['types'=>$types , 'regions'=>$regions , 'estate'=>$estate , 'similarEstates' => $similarEstates->take(6)->get()]);
        $auth = Auth::guard('admin-api')->user();
        return response()->json(['estate' => $estate,'facilities'=>$facilities, 'similarEstates' => $similarEstates->orderBy('created_at', 'desc')->take(6)->get(), 'auth' => $auth]);
    }

    public
    function getUserContact($estate_id)
    {
        $estate = Estate::with('experts', 'owner')->find($estate_id);
        $experts = $estate->experts;
        $phones = array();
        $title = '';
        if (!count($experts)) {
            if ($estate->reference) {
                $title = 'املاک 79';
                array_push($phones, '028-33337536');
            } else {
                $user = User::find($estate->owner_id);
                array_push($phones, $user->phone);
                switch ($estate->advertiser) {
                    case 'agent':
                        $title = 'املاک ' . str_replace('املاک', '', $user->name);
                        break;
                    case 'owner':
                        $title = 'تماس آگهی دهنده ';
                        break;
                }
            }
        } else {
            foreach ($experts as $expert) {
                array_push($phones, $expert->phone);
                if (count($experts) > 1) {
                    $title = ' کارشناسان  املاک79: ';

                } else {

                    $title = ' کارشناس  املاک79: ';
                }
            }
        }

        return response()->json(['title' => $title, 'phones' => $phones]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    {
        $estate = Estate::where('code', $code)->with('owner', 'images', 'city')->first();
        if (!$estate) {
            return response('not exist Estate', 404);
        }
        $type = Type::find($estate->type_id);
        $category = ($estate->category === 'sell' || $estate->category === 'presell') ? 'sell' : 'rent';
        $fields = $type->fields()->wherePivot('category', $category)->get();
        foreach ($fields as $field) {
            foreach ($estate->fields as $key => $value) {
                if ($field->name === $key)
                    if (isset($value)) {
                        if ($key === 'delivery') {
                            $date = Carbon::parse($value['date']);
                            $now = Carbon::now();
                            $field->value = $date->lte($now) ? -1 : $date->diffInMonths($now) + 1;
                        } else
                            $field->value = $value;
                    }
            }
        }

        $hasFacility = $fields->where('name', 'facility')->first();
        $facilities = [];
        if ($hasFacility) {
            $facilities = Facility::all();
            foreach ($facilities as $facility) {
                $facility['isChecked'] = false;
                if (isset($estate->fields['facilities'])) {
                    if (in_array($facility->slug, $estate->fields['facilities']))
                        $facility['isChecked'] = true;
                }


            }
        }
        $regions = Region::where('city_id', $estate->city_id)->orderBy('arrange')->get();
        return response()->json(['estate' => $estate, 'fields' => $fields, 'facilities' => $facilities, 'regions' => $regions], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, $id)
    {
        //        return response($request->fields['has_loan']['value'],404);
        $rules['price'] = $request->category === 'sell' ? 'required|numeric|min:0|max:100000000' : 'required|numeric|min:0';
        $rules['total_price'] = $request->category === 'sell' ? 'required|numeric|min:10000000|max:20000000000' : 'required|numeric|min:0';
//
        $total_price_label = $request->category === 'rent' ? 'رهن' : 'قیمت کل';
        $price_label = $request->category === 'rent' ? 'اجاره ' : 'قیمت ';
        $messages = [
            'total_price.required' => "$total_price_label الزامی است.",
            'price.required' => "$price_label الزامی است.",
            'total_price.max' => "$total_price_label  بیشتر از 20 میلیارد نباشد.",
            'total_price.min' => "$total_price_label  کمتر از 10 میلیون نباشد.",
            'price.max' => "$price_label  بیشتر از 100 میلیون نباشد.",
            'price.min' => "$price_label کمتر از 0 نباشد.",
            'total_price.numeric' => "$total_price_label باید عدد باشد.",
            'price.numeric' => "$total_price_label باید عدد باشد.",
        ];

        $rules = [
            'plan_id' => 'required|numeric',
            'area' => 'required|numeric|min:5',
            'region_id' => 'required|numeric|min:1',
            'type_id' => 'required|numeric|min:1',
            'city_id' => 'required|numeric|min:1',
        ];
        $rules['price'] = $request->category === 'sell' ? 'required|numeric|min:0|max:100000000' : 'required|numeric|min:0';
        $rules['total_price'] = $request->category === 'sell' ? 'required|numeric|min:10000000|max:20000000000' : 'required|numeric|min:0';

        $fields = $request->fields;
        $facility = array_key_exists("facility", $fields);
        foreach ($fields as $key => $value) {
            if ($value['validation'])
                $rules['fields.' . $key . '.value'] = $value['validation'];
        }

        $facilities = $request->facilities;
        if ($facility) {
            if ($facilities['cabinet']['isChecked']) {
                $rules['fields.type_cabinet.value'] = 'required';
            }
        }
        $request->validate($rules, $messages);

//        return response($request,404);
        $estate = Estate::findOrFail($id);

//        $estate->type_id = $request->type_id;
//        $estate->city_id = $request->city_id;
        $estate->region_id = $request->region_id;
        $estate->plan_id = $request->plan_id;
        $estate->title = isset($fields['presell']['value']) ? str_replace('پیش', '', $request->title) : $request->title;
        $estate->price = $request->price;
        $estate->total_price = $request->total_price;
        $estate->area = $request->area;
        $estate->category = isset($fields['presell']['value']) ? 'presell' : $request->category;
        $estate->status = $estate->status === 'FAILED' ? 'FAILED' : "PENDING";
//        $estate->advertiser = Auth::check() ? Auth::user()->role :  'owner';
        if (!empty($facilities)) {
//            $estate->parking = $facilities['parking']['isChecked'] ? true : false;
//            $estate->elevator = $facilities['elevator']['isChecked'] ? true : false;
        }

        $fields['floor']['value'] = isset($fields['floor']['value']) ? gettype($fields['floor']['value']) === 'array' ? $fields['floor']['value'] : array_map('intval', explode(',', str_replace([',', 'و'], ',', $fields['floor']['value']))) : null;
//        isset($fields['exchange']['value']) && $estate->exchange = $fields['exchange']['value'];
//        isset($fields['room']['value']) && $estate->room = $fields['room']['value'];
//        isset($fields['floor']['value']) && $estate->floor = $fields['floor']['value'];
//        isset($fields['tarakom']['value']) && $estate->tarakom = $fields['tarakom']['value'];
//        isset($fields['unit']['value']) && $estate->unit = $fields['unit']['value'];
//        isset($fields['age']['value']) && $estate->age = isset($fields['delivery']['value']) ? 0 : $fields['age']['value'];

        $sync_fields = [];
        $options = $estate->feilds;
        foreach ($fields as $field) {
            if (isset($field['value'])) {

                $options[$field['name']] = $field['value'];
                $sync_fields[$field['id']] = ['value' => $field['name'] === 'floor' ? json_encode($field['value']) : $field['value']];
            }
        }
        if (isset($fields['delivery']['value'])) {
            $options['delivery'] = Carbon::now()->addMonth($fields['delivery']['value']);
            $sync_fields[$fields['delivery']['id']] = ['value' => Carbon::now()->addMonth($fields['delivery']['value'])];
            $options['age'] = 0;
            $sync_fields[$fields['age']['id']] = ['value' => 0];
            $options['housting'] = 'تخلیه';
            $sync_fields[$fields['housting']['id']] = ['value' => 'تخلیه'];
        }

        $estate->properties()->sync($sync_fields);
        $checkedFacility = [];
        $checkedOptions = [];

        if ($facility) {
//            $facilities = Facility::all();
            foreach ($facilities as $facility) {
                if (isset($facility['isChecked']) && $facility['isChecked']) {
                    array_push($checkedFacility, $facility['id']);
                    array_push($checkedOptions, $facility['slug']);
                }

            }

            $estate->facilities()->detach();
            empty(!$checkedFacility) && $estate->facilities()->sync($checkedFacility);
            empty(!$checkedOptions) && $options['facilities'] = $checkedOptions;
        }
        $estate->fields = $options;
        $res = $estate->save();

        if (!$res) {
            return response()->json(['message' => 'خطایی در ثبت ملک به وجود آمده است.'], 400);
        }
        return response()->json(['code' => $estate->code, 'id' => $estate->id], 200);
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
        //
    }

    public function manage($code, $email_from = null)
    {

//        $id = self::getIdquickRandom($code);
//        $id = self::getIdquickRandom($code);
        $estate = Estate::where('code', $code)->whereReference(0)->with('plan', 'properties', 'owner', 'images')->first();
        if (!$estate) {
            return response('no estate', 404);
        }
        $delivery = isset($estate->fields['delivery']);
        if ($delivery) {
            $estate->delivery = jDate($estate->fields['delivery']['date'])->format('%B %Y');
        }
        $estate->date = jdate($estate->created_at)->ago();
        $fields = Field::pluck('label', 'name')->toArray();
        $facilities = Facility::pluck('name', 'slug')->toArray();
        //send sms if user have not login
        $sendSms = false;
//        if($estate->status==='FAILED'){
//            $sendSms = true;
//        }

        $active = true;
        $advertiser = User::find($estate->owner_id);
        $user = Auth::guard('admin-api')->check() ? Auth::guard('admin-api')->user() : (Auth::guard('api')->check() ? Auth::guard('api')->user() : null);
        $sendSms = $estate->status === 'FAILED' && !Auth::guard('admin-api')->check();
        if (!$user) { //if not login enyOne
            if ($email_from === 'undefined' || $email_from === 'landlord') {
                $sendSms = true;
            } elseif ($email_from === 'amlak') {
                return response('redirect to login page', 404);
            }
        } else { //if Login
//            if ($user->role === 'agent' || $user->role === 'owner') {
//                if ($user->phone !== $estate->owner->phone) {
//                    $sendSms = true;
//                }
//            }
        }
        if ($sendSms) {
            $active = false;
            $code = rand(1000, 9999);
            $advertiser->password = Hash::make($code);
            $advertiser->save();
            self::sendSms($advertiser->phone, $code);
        }
        $estate->owner->active = $active;
        return response(['estate' => $estate, 'fields' => $fields, 'facilities' => $facilities], 200);
    }

    public
    static function quickRandom($id)
    {
        $length = 16;
        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length) . $id;
    }

//
//    public static function getIdquickRandom($randId)
//    {
//        return str_replace(array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z')
//            , '', $randId);
//    }

    public
    function removeEstate($id)
    {
        $estate = Estate::findOrFail($id);
        $estate->status = 'REMOVED';
        $estate->save();
    }

    public
    function changeFailStatus($id)
    {
        $estate = Estate::findOrFail($id);
        $estate->status = 'PENDING';
        $estate->save();
    }

    public
    function setPasswordAndSendSms($phone, $code)
    {
        $user = User::where('phone', $phone)->first();
        $user->password = Hash::make($code);
        $user->save();
        self::sendSms($phone, $code);
    }

    public
    function getUserEstate()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response('no user', 404);
        }
        $estates = Estate::where('owner_id', $user->id)->get();
        return $estates;
    }

}
