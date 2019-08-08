<?php

namespace App\Console\Commands;

use App\Darkhast;
use App\Estate;
use App\Services\Sms;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class sendSmsDarkhast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'darkhast:sendSms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send sms for user`s darkhasts dialy';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $darkhast = Darkhast::find(84);
//        $darkhast->expired_at=Carbon::now()->addDay(5) ;
//        $darkhast->save();
//        dd($darkhast->updated_at);
        $now = Carbon::now();
        $yesterday = $now->addDay(-1);
        $darkhasts_user_id = Darkhast::where('expired_at', '>', $now)
            ->where('updated_at', '>', $yesterday)
            ->whereColumn('updated_at', '>', 'created_at')->orderBy('updated_at', 'desc')->pluck('user_id', 'updated_at')->toArray();
//        asort($darkhasts_user_id);
        $user_ids = array_unique($darkhasts_user_id);
        $response = [];
        $phones = User::where(function ($q) use ($user_ids) {
            foreach($user_ids as $updated_at=>$user_id){
                $q->orWhere([['id',$user_id],['last_login','<',$updated_at]]);
            }
            return $q;
        })->pluck('phone', 'code')->toArray();
        foreach ($phones as $user_code => $phone) {
            $sendSms = new Sms;
            $message = "کاربر گرامی ملکهای جدیدی مطابق درخواست شما در سایت ثبت شده است.  amlak79.ir/da/$user_code  ";
            $response[] = $sendSms->sendSms($message, $phone);
        }
        $response = json_encode($response);
        $this->info($response);
    }
//$now = Carbon::now();
//        $user_ids = [];
//        $response = [];
//        $yesterday = Carbon::now()->addDay(-1);
//        $estates = Estate::where('status', 'PUBLISHED')->where('created_at', '>=', $yesterday)->get();
//        foreach ($estates as $estate) {
//            $category = $estate->category;
//            $sell = $category === 'sell' ? true : false;
//            $estate_total_price = $estate->total_price;
//            $darkhasts = Darkhast::where('category', $category)->where('type_id', $estate->type_id)
//                ->whereJsonContains('region_ids', $estate->region_id)
//                ->when($sell, function ($q) use ($estate) {
//                    $q->where('max_price', '>=', $estate->total_price)
//                        ->where(function ($qu) use ($estate) {
//                            return $qu->whereNull('min_price')->orWhere('min_price', '<=', $estate->total_price);
//                        });
//                    $q->where('min_area', '<=', $estate->area)->where(function ($qe) use ($estate) {
//                        return $qe->whereNull('max_area')->orWhere('max_area', '>=', $estate->area);
//                    });
//                    $q->when(isset($estate->fields['room']), function ($q) use ($estate) {
//                        return $q->where(function ($qe) use ($estate) {
//                            return $qe->whereNull('room')->orWhere('room', '<=', $estate->fields['room']);
//                        });
//                    });
//                    $q->when(isset($estate->fields['facilities']) && !in_array('elevator', $estate->fields['facilities']), function ($q) {
//                        $q->where('elevator', 0);
//                        return $q;
//                    });
//                    $q->when(isset($estate->fields['facilities']) && !in_array('parking', $estate->fields['facilities']), function ($q) {
//                        $q->where('parking', 0);
//                        return $q;
//                    });
//                    return $q;
//                }, function ($q) use ($estate) {
//                    return $q->where('max_mortgage', '>=', $estate->total_price)->where(function ($query) use ($estate) {
//                        return $query->whereNull('min_mortgage')->orWhere('min_mortgage', '<=', $estate->total_price);
//                    })
//                        ->where('max_rent', '>=', $estate->price)->where(function ($query) use ($estate) {
//                            return $query->whereNull('min_rent')->orWhere('min_rent', '<=', $estate->price);
//                        })
//                        ->where('min_area', '<=', $estate->area)->where(function ($query) use ($estate) {
//                            return $query->WhereNull('max_area')->orWhere('max_area', '>=', $estate->area);
//                        })
//                        ->when(isset($estate->fields['room']), function ($q) use ($estate) {
//                            return $q->where(function ($qe) use ($estate) {
//                                return $qe->whereNull('room')->orWhere('room', '<=', $estate->fields['room']);
//                            });
//                        })
//                        ->when(isset($estate->fields['facilities']) && !in_array('elevator', $estate->fields['facilities']), function ($q) {
//                            $q->where('elevator', 0);
//                            return $q;
//                        })
//                        ->when(isset($estate->fields['facilities']) && !in_array('parking', $estate->fields['facilities']), function ($q) {
//                            $q->where('parking', 0);
//                            return $q;
//                        });
//                })->get();
//            foreach ($darkhasts as $darkhast) {
//                $darkhast->addSimilarEstate($estate->id);
//                if ($darkhast->expired_at >= $now) {
//                    $user_ids[] = $darkhast->user_id;
//                }
//            }
//        }
//
//        $user_ids = array_unique($user_ids);
//        $phones = [];
//        foreach ($user_ids as $user_id) {
//            $user = User::find($user_id);
//            $phones[$user->code] = $user->phone;
//        }
//        $phones = array_unique($phones);
//        foreach ($phones as $user_code => $phone) {
//            $username = "saeid1081";
//            $password = '9127816488';
//            $from = "+100020400";
//            $pattern_code = "1188";
//            $to = array($phone);
//            $link = "کاربر گرامی ملکهای جدیدی مطابق درخواست شما در سایت ثبت شده است.  amlak79.ir/darkhast/list/$user_code  ";
//            $input_data = array("tracking-code" => $link, "name" => "املاک 79");
//
//            $url = "http://37.130.202.188/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=$pattern_code";
////        $url = "http://37.130.202.188/patterns/pattern?username=" . $username . "&password=" . urlencode($password) . "&from=$from&to=" . json_encode($to) . "&input_data=" . $code . "&pattern_code=$pattern_code";
//            $handler = curl_init($url);
//            curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
//            curl_setopt($handler, CURLOPT_POSTFIELDS, $input_data);
//            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
//            $response[] = curl_exec($handler);
//        }
//        $response = json_encode($response);
//        $this->info($response);
//    }

}
