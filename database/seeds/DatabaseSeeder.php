<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UserSeeder::class);

        DB::unprepared(file_get_contents(base_path('database/seeds/sql/properties.sql')));
        DB::unprepared(file_get_contents(base_path('database/seeds/sql/fields.sql')));


        //fill Types Table
        $types = array('آپارتمان','مغازه/تجاری','اداری','زمین/کلنگی','خانه/ویلا','باغ و کشاورزی','کارخانه/کارگاه');
        foreach($types as $key=>$value){
            $type =new \App\Type();
            $type->name = $value;
            $type->save();
        }

        //fill City Table

        $cities = array('qazvin'=>'قزوین','abeyek'=>'آبیک','takestan'=>'تاکستان','avaj'=>'آوج','mohamadiye'=>'محمدیه','mehregan'=>'مهرگان','shal'=>'شال','sharifiye'=>'شریفیه','ziya-abad'=>'ضیاآباد','abegarm'=>'آبگرم','danesfahan'=>'دانسفهان','bidestan'=>'بیدستان');
        foreach ($cities as $key=>$value){
            $city = new \App\City();
            $city->name = $value;
            $city->slug = $key;
            $city->save();
        }

       // fill Region Table
        $regions = array('دانشگاه','سرتک','ولایت','ملاصدرا','جانبازان','پونک','آبگیلک','نوروزیان','کوی قضات','غیاث آباد','شهرک شهید رجایی','مینودر','کوثر','بلوار مدرس','پادگان','عارف','فردوسی','فلسطین','شهید بهشتی','نادری','خیام','توحید','ولیعصر','حکم آباد','بوعلی','شهرداری','طالقانی','ملک آباد','هلال احمر','باغ دبیر','بلاغی','هفت سنگان','مولوی','تهران قدیم','منتظری','شهید انصاری','بلوار اسد آبادی','سپه','پیغمبریه','سعدی','نظام وفا','نواب','خیابان امام','راه آهن');
        $cities = \App\City::all();
        foreach ($cities as $city){
            if ( $city->slug === 'qazvin'){
                foreach ($regions as $key => $value) {
                    $region = new \App\Region();
                    $region->city_id = $city->id;
                    $region->name = $value;
                    $region->save();
                }
            }
            else{
                $region = new \App\Region();
                $region->city_id = $city->id;
                $region->name = $city->name;
                $region->save();
            }
        }

//      fill PLANS table
        $plans = array('شمالی','جنوبی','شرقی-غربی','دوکله','دو نبش','سه نبش');
        foreach ($plans as $key=>$value){
            $plan = new \App\Plan();
            $plan->name = $value;
            $plan->save();
        }

        // fill Estates Table
        $estate = new \App\Estate();
        $estate->user_id =1;
        $estate->type_id =1;
        $estate->category ='sell';
        $estate->city_id =1;
        $estate->region_id = 1;
        $estate->plan_id = 1;
        $estate->price =100000000;
        $estate->meter_price =100000;
        $estate->area =100;
        $estate->address ='خ دانشگاه';
        $estate->status ='PUBLISHED';
        $estate->save();

        $estate = new \App\Estate();
        $estate->user_id =1;
        $estate->type_id =2;
        $estate->category ='presell';
        $estate->city_id =1;
        $estate->region_id = 1;
        $estate->plan_id = 1;
        $estate->price =100000000;
        $estate->meter_price =100000;
        $estate->area =100;
        $estate->address ='خ دانشگاه';
        $estate->status ='PUBLISHED';
        $estate->save();

        $estate = new \App\Estate();
        $estate->user_id =1;
        $estate->category ='rent';
        $estate->type_id =3;
        $estate->city_id =2;
        $estate->price =300000000;
        $estate->region_id = 1;
        $estate->plan_id = 2;
        $estate->meter_price =300000;
        $estate->area =90;
        $estate->status ='PUBLISHED';
        $estate->address ='نوروزیان';
        $estate->save();

        $facilities = ['parking'=>'پارکینگ','elevator'=>'آسانسور','store'=>'انباری','terrace'=>'تراس','sauna'=>'سونا','jacuzzi'=>'جکوزی','pool'=>'استخر','toilet'=>'سرویس فرنگی','ff'=>'آیفون تصویری','gaz_cooler'=>'کولر گازی','aircondition'=>'هواساز','package'=>'پکیج','floor_heating'=>'گرمایش از کف','parket'=>'پارکت','furnished'=>'مبله شده','cabinet'=>'کابینت'];
        foreach($facilities as $slug=>$name){
            DB::table('facilities')->insertGetId(
                ['name' => $name, 'slug' => $slug]
            );
        }

        $user =new \App\User();
        $user->name = 'elham salimi';
        $user->phone = '09192862195';
        $user->role = 'owner';
        $user->password = \Illuminate\Support\Facades\Hash::make('123123');
        $user->save();

        $email = new \App\Email();
        $email->User_id = $user->id;
        $email->email = 'salimielham65@gmail.com';
        $email->save();

        $user =new \App\User();
        $user->name = 'amlak79 ';
        $user->phone = '09124819312';
        $user->password = \Illuminate\Support\Facades\Hash::make('123456');
        $user->role = 'super_admin';
        $user->save();

        $email = new \App\Email();
        $email->user_id = $user->id;
        $email->email = 'programmer1365@gmail.com';
        $email->save();

        $telephone = new \App\Telephone();
        $telephone->user_id = $user->id;
        $telephone->telephone = '02833667788';
        $telephone->save();

    }
}
