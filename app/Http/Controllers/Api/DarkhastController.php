<?php

namespace App\Http\Controllers\Api;

use App\Darkhast;
use App\Email;
use App\Estate;
use App\Region;
use App\Systems\Random;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPSTORM_META\map;

class DarkhastController extends Controller
{
    public function showDarkhastList()
    {
        $user = Auth::guard('api')->user();
        $darkhsts = $user->
        $darkhast = Darkhast::find(1);
        $regions_ids = json_decode($darkhast->region_ids);
        $regions = Region::all();
        $regions_name = null;
        foreach ($regions_ids as $regions_id) {
            $regions_name .= " ," . $regions[$regions_id]->name;
        }

        return $regions_name;
    }

    public function storeSell(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|max:11',
            'region' => 'required',
            'region.*' => 'required',
            'min_area' => 'required|numeric|min:0',
            'category' => 'in: sell,rent',
            'max_price' => 'required|numeric|min:0',
        ]);
//        $token = null;
//        $user = Auth::guard('api')->check()
//            ? Auth::guard('api')->user()


        $user = User::updateOrCreate(['phone' => $request->phone], ['name' => $request->name]);
        if ($user->code === null) {
            createRandom:
            $rand_code = Random::generate(15);
            $exist_user = User::where('code', $rand_code)->first();
            if ($exist_user) {
                goto createRandom;
            }
            $user->code = $rand_code;
            $user->save();
        }
        if ($request->filled('email')) {
            $email = [];
            foreach ($user->emails as $item) {
                if ($item->email === $request->email)
                    $email = $item;
            }
            if (empty($email)) {
                $email = new Email();
                $email->email = $request->email;
                $email->user_id = $user->id;
                $email->save();
            }
        }
        $current_date = Carbon::now();
        $darkhast = new Darkhast();
        $darkhast->user_id = $user->id;
        $darkhast->category = 'sell';
        $darkhast->type_id = $request->type_id;
        $darkhast->region_ids = $request->region;
        $darkhast->min_price = $request->min_price;
        $darkhast->max_price = $request->max_price;
        $darkhast->min_area = $request->min_area;
        $darkhast->max_area = $request->max_area;
        $darkhast->room = $request->room;
        $request->filled('elevator') ? $darkhast->elevator = $request->elevator : $darkhast->elevator = false;
        $request->filled('parking') ? $darkhast->parking = $request->parking : $darkhast->parking = false;
        $darkhast->expired_at = $current_date->addDays(30);
        $darkhast->created_at = $current_date;
        $darkhast->updated_at = $current_date;
        $darkhast->save();
        //در صورت داشتن ایمیل برای کاربر ایمیل ثبت درخواست ارسال شود.
        return response(['darkhast' => $darkhast], 200);
    }

    public function storeRent(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|max:11',
            'region' => 'required',
            'region.*' => 'required',
            'min_area' => 'required|numeric|min:0',
            'category' => 'in: sell,rent',
            'max_rent' => 'required|numeric|min:0',
            'max_mortgage' => 'required|numeric|min:0',
        ]);
//        $user = Auth::guard('api')->check()
//            ? Auth::guard('api')->user()
//            : User::firstOrCreate(['phone' => $request->phone], ['name' => $request->name]);

        $user = User::updateOrCreate(['phone' => $request->phone], ['name' => $request->name]);
        if ($user->code === null) {
            createRandom:
            $rand_code = Random::generate(15);
            $exist_user = User::where('code', $rand_code)->first();
            if ($exist_user) {
                goto createRandom;
            }
            $user->code = $rand_code;
            $user->save();
        }
//        $token = null;
//        if (!Auth::check()) {
//            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
//        }
        if ($request->filled('email')) {
            $email = [];
            foreach ($user->emails as $item) {
                if ($item->email === $request->email)
                    $email = $item;
            }
            if (empty($email)) {
                $email = new Email();
                $email->email = $request->email;
                $email->user_id = $user->id;
                $email->save();
            }
        }
        $current_date = Carbon::now();
        $darkhast = new Darkhast();
        $darkhast->user_id = $user->id;
        $darkhast->category = 'rent';
        $darkhast->type_id = $request->type_id;
        $darkhast->region_ids = $request->region;
        $darkhast->min_area = $request->min_area;
        $darkhast->max_area = $request->max_area;
        $darkhast->min_rent = $request->min_rent;
        $darkhast->max_rent = $request->max_rent;
        $darkhast->min_mortgage = $request->min_mortgage;
        $darkhast->max_mortgage = $request->max_mortgage;
        $darkhast->room = $request->room;
        $request->filled('elevator') ? $darkhast->elevator = $request->elevator : $darkhast->elevator = false;
        $request->filled('parking') ? $darkhast->parking = $request->parking : $darkhast->parking = false;
        $darkhast->expired_at = $current_date->addDays(30);
        $darkhast->created_at = $current_date;
        $darkhast->updated_at = $current_date;
        $darkhast->save();
        //در صورت داشتن ایمیل برای کاربر ایمیل ثبت درخواست ارسال شود.
//        return response(['darkhast' => $darkhast, 'token' => $token], 200);
        return response(['darkhast' => $darkhast], 200);
    }

    public function getUserDarkhasts(Request $request)
    {

        $user = User::where('code', $request->user_code)->first();
        if (!$user) {
            return response('کاربری با این شماره کاربری یافت نشد.', 404);
        }
        $user->last_login = Carbon::now();
        $user->save();
        $emails = $user->emails;
        $now = Carbon::now();

        $darkhasts = Darkhast::where('user_id', $user->id)->latest()->get();
        $similar_ids = [];
        $i = 0;
        foreach ($darkhasts as $darkhast) {
            if ($darkhast->similar_estate_ids) {
                $similar_ids[$i] = $darkhast->similar_estate_ids;
                $i++;
            }
        }
        $result = array();
        foreach ($similar_ids as $subarray) {
            $result = array_merge($result, $subarray);
        }
        //delete duplicate estate
        $similar_estate_ids = array_unique($result);
        $similarEstates = Estate::whereIn('id', $similar_estate_ids)->with('images')->where('status', 'PUBLISHED')->orderBy('created_at', 'desc')->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            });
        return response()->json(['user' => $user, 'darkhasts' => $darkhasts, 'similarEstates' => $similarEstates]);

    }

    public function removeDarkhastById(Request $request)
    {
        $id = $request->id;
        $darkhast = Darkhast::find($id);
        if (!$darkhast) {
            return response('Not Fond  darkhast!', 404);
        }
        $result = $darkhast->delete();
        //delete similar estate
//        $darkhasts = Auth::user()->darkhasts;
        return redirect()->action('Api\DarkhastController@getUserDarkhasts', ['user_code' => $request->user_code]);
//        return response()->jsone(['darkhasts' => $darkhasts, 'similarEstates' => $similarEstate]);
    }

    public function removeAllDarkhastOfUser()
    {
        $user = User::where('code', request()->user_code)->first();
        if (!$user) {
            return response('User Not found', 404);
        }
//        $user = Auth::guard('api')->user();
        $user->darkhasts()->delete();
        return response('darkhasts removed successfully', 200);
        return redirect()->action('Api\DarkhastController@getUserDarkhasts');

        return response('all darkhsts deleted', 200);
    }

    public function removeDarkhastEstate(Request $request)
    {
        //نغییر id به code
        $user_code = $request->user_code;
        $user = User::where('code', $user_code)->first();
        if (!$user) {
            return response('کاربری با این شماره کاربری یافت نشد.', 404);
        }
        $darkhasts = Darkhast::where('user_id', $user->id)->get();
        foreach ($darkhasts as $darkhast) {
            $darkhast->removeSimilarEstate($request->estate_id);
        }
//        $user->last_login = Carbon::now()->addRealSecond();
//        $user->save();
        return redirect()->action('Api\DarkhastController@getUserDarkhasts', ['user_code' => $user_code]);
        return response('estate removed successfully!', 200);
    }


    public function cronJob()
    {
        $now = Carbon::now();
        $user_ids = [];
        $response = [];
        $yesterday = Carbon::now()->addDay(-1);
        $estates = Estate::where('status', 'PUBLISHED')->where('created_at', '>=', $yesterday)->get();
        foreach ($estates as $estate) {
            $category = $estate->category;
            $sell = $category === 'sell' ? true : false;
            $estate_total_price = $estate->total_price;
            $darkhasts = Darkhast::where('category', $category)->where('type_id', $estate->type_id)
                ->whereJsonContains('region_ids', $estate->region_id)
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
            foreach ($darkhasts as $darkhast) {
                $darkhast->addSimilarEstate($estate->id);
                if ($darkhast->expired_at >= $now) {
                    $user_ids[] = $darkhast->user_id;
                }
            }
        }

        $user_ids = array_unique($user_ids);
        $phones = [];
        foreach ($user_ids as $user_id) {
            $user = User::find($user_id);
            $phones[$user->code] = $user->phone;
        }
        $phones = array_unique($phones);
        foreach ($phones as $user_code => $phone) {
            $username = "saeid1081";
            $password = '9127816488';
            $from = "+100020400";
            $pattern_code = "1188";
            $to = array($phone);
            $link = "کاربر گرامی ملکهای جدیدی مطابق درخواست شما در سایت ثبت شده است.  amlak79.ir/darkhast/list/$user_code  ";
            $input_data = array("tracking-code" => $link, "name" => "املاک 79");

            $url = "http://37.130.202.188/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
//        $url = "http://37.130.202.188/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . $code . "&pattern_code=$pattern_code";
            $handler = curl_init($url);
            curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            $response[] = curl_exec($handler);
        }
        return $response;
    }


}
