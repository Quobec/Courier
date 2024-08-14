<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SMSApiServiceProvider extends ServiceProvider
{
    public function __construct(){

    }  

    public function sendSMS(string $phone, string $message)
    {

        $params = array(
            'to' => $phone, // Receiver's phone numbers, seperated by commas.

            'from' => env('APP_NAME'), // Sender name set on: https://ssl.smsapi.pl/sms_settings/sendernames 
            // If you have a test account, only "Test" is accepted for this parameter.

            'message' => $message, // SMS content

            'format' => 'json'
        );

        $backup = false;

        $token = env("SMSAPI_TOKEN");

        static $content;

        if ($backup == true) {
            $url = 'https://api2.smsapi.pl/sms.do';
        } else {
            $url = 'https://api.smsapi.pl/sms.do';
        }

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $params);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token"
        ));

        $content = curl_exec($c);
        $http_status = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if ($http_status != 200 && $backup == false) {
            $backup = true;
        }

        curl_close($c);
        return $content;
    }
}
