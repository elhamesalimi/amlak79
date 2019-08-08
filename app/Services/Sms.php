<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 24/07/2019
 * Time: 10:17 AM
 */

namespace App\Services;

use SoapClient;
use SoapFault;

class Sms
{
    private $username;
    private $password;
    private $fromNum;
    private $client;

    public function __construct()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $this->client = new SoapClient("http://37.130.202.188/class/sms/wsdlservice/server.php?wsdl");
        $this->username = config('services.sms.USERNAME');
        $this->password = config('services.sms.PASSWORD');
        $this->fromNum = config('services.sms.FROM_NUM');
    }

    public function sendSms($messageContent, $phone)
    {
        $client = $this->client;
        $user = $this->username;
        $pass = $this->password;
//        $fromNum = $this->fromNum;
        $fromNum = '+983000505';

        $toNum = array($phone);
        $time='';
        $op = 'send';
        $status = $client->SendSMS($fromNum, $toNum, $messageContent, $user, $pass, $time, $op);
        return $status;
    }

    public function patterns()
    {
        $client = $this->client;
        $user = $this->username;
        $pass = $this->password;
        $fromNum = $this->fromNum;
        $toNum = array('09192862195');
        $pattern_code = "1530";
        $input_data = array( 'tracking-code'=>"salam");

        $result = $client->sendPatternSms($fromNum,$toNum,$user,$pass,$pattern_code,$input_data);
        dd($result);
    }
}