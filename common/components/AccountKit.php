<?php


namespace common\components;


use Yii;
use yii\helpers\ArrayHelper;

class AccountKit
{
    public static function getConfig() {
        $ParamConfigAccountKit = ArrayHelper::getValue(Yii::$app->params,'account_kit',[]);
        $ConfigAccountKit = ArrayHelper::getValue($ParamConfigAccountKit,'store_'.Yii::$app->storeManager->getId(),[]);
        return $ConfigAccountKit;
    }

    public static function getAppId() {
        $ConfigAccountKit = self::getConfig();
        return ArrayHelper::getValue($ConfigAccountKit,'app_id','');
    }
    public static function getSecret() {
        $ConfigAccountKit = self::getConfig();
        return ArrayHelper::getValue($ConfigAccountKit,'secret','');
    }
    public static function getVersion() {
        $ConfigAccountKit = self::getConfig();
        return ArrayHelper::getValue($ConfigAccountKit,'ver','');
    }

    public static function getAccessToken($code){

        $token_exchange_url = 'https://graph.accountkit.com/' . self::getVersion() . '/access_token?' .
            'grant_type=authorization_code' .
            '&code=' . $code.
            "&access_token=AA|".self::getAppId()."|".self::getSecret();
        $data = self::doCurl($token_exchange_url);
        return $data;
    }
    public static function getInfo($user_access_token){
        $me_endpoint_url = 'https://graph.accountkit.com/' . self::getVersion() . '/me?' .
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