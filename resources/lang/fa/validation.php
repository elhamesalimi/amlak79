<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"         => ":attribute باید پذیرفته شده باشد.",
    "active_url"       => "آدرس :attribute معتبر نیست",
    "after"            => ":attribute باید تاریخی بعد از :date باشد.",
    "alpha"            => ":attribute باید شامل حروف الفبا باشد.",
    "alpha_dash"       => ":attribute باید شامل حروف الفبا و عدد و خظ تیره(-) باشد.",
    "alpha_num"        => ":attribute باید شامل حروف الفبا و عدد باشد.",
    "array"            => ":attribute باید شامل آرایه باشد.",
    "before"           => ":attribute باید تاریخی قبل از :date باشد.",
    "between"          => array(
        "numeric" => ":attribute باید بین :min و :max باشد.",
        "file"    => ":attribute باید بین :min و :max کیلوبایت باشد.",
        "string"  => ":attribute باید بین :min و :max کاراکتر باشد.",
        "array"   => ":attribute باید بین :min و :max آیتم باشد.",
    ),
    "boolean"          => "The :attribute field must be true or false",
    "confirmed"        => ":attribute با تاییدیه مطابقت ندارد.",
    "date"             => ":attribute یک تاریخ معتبر نیست.",
    "date_format"      => ":attribute با الگوی :format مطاقبت ندارد.",
    "different"        => ":attribute و :other باید متفاوت باشند.",
    "digits"           => ":attribute باید :digits رقم باشد.",
    "digits_between"   => ":attribute باید بین :min و :max رقم باشد.",
    "email"            => "فرمت :attribute معتبر نیست.",
    "exists"           => ":attribute انتخاب شده، معتبر نیست.",
    "image"            => ":attribute باید تصویر باشد.",
    "in"               => ":attribute انتخاب شده، معتبر نیست.",
    "integer"          => ":attribute باید نوع داده ای عددی (integer) باشد.",
    "ip"               => ":attribute باید IP آدرس معتبر باشد.",
    "max"              => array(
        "numeric" => ":attribute نباید بزرگتر از :max باشد.",
        "file"    => ":attribute نباید بزرگتر از :max کیلوبایت باشد.",
        "string"  => ":attribute نباید بیشتر از :max کاراکتر باشد.",
        "array"   => ":attribute نباید بیشتر از :max آیتم باشد.",
    ),
    "mimes"            => ":attribute باید یکی از فرمت های :values باشد.",
    "min"              => array(
        "numeric" => ":attribute نباید کوچکتر از :min باشد.",
        "file"    => ":attribute نباید کوچکتر از :min کیلوبایت باشد.",
        "string"  => ":attribute نباید کمتر از :min کاراکتر باشد.",
        "array"   => ":attribute نباید کمتر از :min آیتم باشد.",
    ),
    "not_in"           => ":attribute انتخاب شده، معتبر نیست.",
    "numeric"          => ":attribute باید شامل عدد باشد.",
    "regex"            => ":attribute یک فرمت معتبر نیست",
    "required"         => "فیلد :attribute الزامی است",
    "required_if"      => "فیلد :attribute هنگامی که :other برابر با :value است، الزامیست.",
    "required_with"    => "فیلد :attribute الزامی است",
    "required_with_all"=> ":attribute الزامی است زمانی که :values موجود است.",
    "required_without" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "required_without_all" => ":attribute الزامی است زمانی که :values موجود نیست.",
    "same"             => ":attribute و :other باید مانند هم باشند.",
    "size"             => array(
        "numeric" => ":attribute باید برابر با :size باشد.",
        "file"    => ":attribute باید برابر با :size کیلوبایت باشد.",
        "string"  => ":attribute باید برابر با :size کاراکتر باشد.",
        "array"   => ":attribute باسد شامل :size آیتم باشد.",
    ),
    "timezone"         => "The :attribute must be a valid zone.",
    "unique"           => ":attribute قبلا انتخاب شده است.",
    "url"              => "فرمت آدرس :attribute اشتباه است.",
    "recaptcha"         => "فیلد :attribute به درستی انتخاب نشده است.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => array(),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => array(
        "name" => "نام",
        "family" => "فامیلی",
        "username" => "نام کاربری",
        "email" => "ایمیل",
        "first_name" => "نام",
        "last_name" => "نام خانوادگی",
        "password" => "رمز عبور",
        "password_confirmation" => "تاییدیه ی رمز عبور",
        "city" => "شهر",
        "country" => "کشور",
        "address" => "آدرس",
        "phone" => "تلفن",
        "mobile" => "موبایل",
        "age" => "سن",
        "sex" => "جنسیت",
        "gender" => "جنسیت",
        "day" => "روز",
        "month" => "ماه",
        "year" => "سال",
        "hour" => "ساعت",
        "minute" => "دقیقه",
        "second" => "ثانیه",
        "title" => "عنوان",
        "text" => "متن",
        "content" => "توضیحات",
        "description" => "توضیحات",
        "excerpt" => "گلچین کردن",
        "date" => "تاریخ",
        "time" => "زمان",
        "available" => "موجود",
        "size" => "اندازه",
        "category" => "دسته بندی",
        "code" => "کد شرکت",
        "unit" => "واحد",
        "summary" => "خلاصه خبر",
        "photo" => "عکس ",
        "avatar" => "آواتار",
        "compony" => "شرکت",
        "job" => "سمت شغلی",
        "level" => "سطح حادثه",
        "file" => "فایل",
        "reportid" => "شماره گزارش",
        "cat_name" => "عنوان دسته بندی",
        "Title" =>"عنوان",
        "Url" => 'آدرس سایت',
        "image" => 'عکس',
        "start_time" => 'ساعت شروع',
        "end_time"   =>  'ساعت پایان',
        'start_date' =>'تاریخ شروع',
        'end_date'   => 'تاریخ پایان',
        'report' => 'گزارش',
        'customer_full_name' => 'نام و نام خانوادگی مشتری',
        'customer_national_code' => 'شماره ملی مشتری',
        'customer_postal_code' => 'کد پستی مشتری',
        'customer_file' => 'قایل بارگذاری شده مشتری',
        'customer_mobile' => 'شماره همراه',
        'customer_phone' => 'تلفن ثابت',
        'customer_telegram' => '  شماره تلگرام مشتری',
        'customer_email' => 'آدرس ایمیل مشتری',
        'customer_address' => 'آدرس مشتری',
        'company_name' => '',
        'manager_name' => '',
        'economic_code' => '',
        'national_id' => '',
        'company_postal_code' => '',
        'copmany_phone' => '',
        'office_phone' => '',
        'company_mobile' => '',
        'office_mobile' => '',
        'company_telegram' => '',
        'company_address' => '',
        'office_address' => '',
        'bidder' => 'مناقصه گذار',
        'title' => 'عنوان',
        'body' => 'متن آگهی',
        'excerpt' => 'خلاصه',
        'slug' => 'نامک',
        'release_date' => 'تاریخ انتشار',
        'start_receipt_deadline' => 'تاریخ شروع مهلت دریافت',
        'deadline' => 'مهلت ارسال',
        'end_receipt_deadline' => 'تاریخ پایان مهلت دریافت',
        'source' => 'منبع',
        'fax' => 'فکس',
        'document_url' => 'بارگذاری سند',
        'national_code' => 'کد ملی',
        'g-recaptcha-response' => 'کپچا',
        'old_password' => 'رمز عبور فعلی',
        'category_id' => 'دسته بندی',
        'carbohydrate' => 'کربوهیدرات',
        'protein' => 'پروتئین',
        'fat' => 'چربی',
        'unit_id.*' => 'واحد',
        'calory.*' => 'کالری',
        'role_id' => 'مقام',
        'admin_id' => 'نام مدیر',
        'plan_id' => 'موقعیت',
        'loan_amount' => 'مبلغ وام',
        'area' => ' متراژ',
        'age' => 'عمر بنا',
        'room' => 'تعداد اتاق',
        'floor' => 'طبقه',
        'unit' => 'واحد',
        'housting' => 'وضعیت سکونت',
        'delivery' => 'زمان تحویل',
        'bahr' => 'عرض زمین',
        'tarakom' => 'تراکم',
        'region_id' => 'منطقه',
        'min_mortgage' => 'حداقل رهن',
        'max_mortgage' => 'حداکثر رهن',
        'min_area' => 'حداقل متراژ',
        'min_price' => 'حداقل قیمت',
        'max_price' => 'حداکثر قیمت',
        'min_rent' => 'حداقل اجاره',
        'max_rent' => 'حداکثر اجاره',
        'region' => 'منطقه',
        'doc' => 'نوع سند',
        'exchange_with' => 'معاوضه با',
        'fields.age.value' => 'عمر بنا',
        'fields.room.value' => 'تعداد اتاق',
        'fields.floor.value' => 'طبقه',
        'fields.unit.value' => 'تعداد کل واحد',
        'fields.housting.value' => 'وضعیت سکونت',
        'fields.delivery.value' => 'زمان تحویل',
        'fields.bahr.value' => 'عرض زمین',
        'fields.tarakom.value' => 'تراکم',
        'fields.doc.value' => 'نوع سند',
        'total_price' => 'قیمت کل',
        'fields.address.value' => 'آدرس',
        'fields.exchange_with.value' => 'معاوضه با',
        'fields.exchange.value' => 'معاوضه',
        'fields.type_cabinet.value' => 'نوع کابینت',
        'fields.delivery.value' => 'زمان تحویل',
        'fields.loan_amount.value' => 'مبلغ وام',
        'fields.has_loan.value' => 'وام دارد',
        'type_cabinet' => 'نوع کابینت',
        'price' => 'قیمت',
        'total_price' => 'قیمت کل',
        '' => '',







    ),
);
