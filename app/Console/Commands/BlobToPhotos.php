<?php

namespace App\Console\Commands;

use App\Darkhast;
use App\Estate;
use App\Http\Controllers\Api\DarkhastController;
use App\Photo;
use App\Services\Sms;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Notifications\publishedToTelegram;


class BlobToPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:exec';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converts blob to photos';

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

//        \Log::info('cron job ok');
//        $oneHourAgo = Carbon::now()->addHour(-1);
//        $darkhasts = Darkhast::where('updated_at', '>=', $oneHourAgo)->get();
//        dd($darkhasts);

        $estate = Estate::find(1468);
        $estate->notify(new publishedToTelegram());
//        $estate->addToSimilarDarkhasts();

//$photos = Photo::all();
//        foreach ($photos as $photo){
//            $photo_id = $photo->id;
//            file_put_contents('C:\Users\Mithun\ecommercephotos\product'.$photo_id.'.jpg', $photo->photo);
//        }
    }
}
