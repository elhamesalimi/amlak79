<?php

namespace App\Console\Commands;

use App\Services\Sms;
use Illuminate\Console\Command;

class sendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' send sms to landlord';

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
        $sendSms = new Sms;
        $sendSms->sendSms('hello! Dear Landlord', '09192862195');
    }
}
