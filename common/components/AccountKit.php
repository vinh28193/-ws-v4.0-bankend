<?php


namespace common\components;


use Yii;

class AccountKit
{
    public static $app_id = '216590825760272';
    public static $secret = '5c7a8ea1b0017499f3e659ad431c87b0';
    public static $version = 'v1.0';
    public static function getAccessToken($code){
        $token_exchange_url = 'https://graph.accountkit.com/' . self::$version . '/access_token?' .
            'grant_type=authorization_code' .
            '&code=' . $code.
            "&access_token=AA|".self::$app_id."|".self::$secret;
        $data = self::doCurl($token_exchange_url);
        return $data;
    }
    public static function getInfo($user_access_token){
        $me_endpoint_url = 'https://graph.accountkit.com/' . self::$version . '/me?' .
            'access_token=' . $user_access_token;
        $data = self::doCurl($me_endpoint_url);
        return $data;
    }
    public static function logout($user_access_token){
        $logout_end_point = "https://graph.accountkit.com/v1.3/logout?access_token=".$user_access_token;
        return self::doCurl($logout_end_point,'POST');
    }
    static function doCurl($url,$method = 'GET')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $data;
    }
}