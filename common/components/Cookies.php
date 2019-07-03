<?php


namespace common\components;


use Yii;
use yii\helpers\ArrayHelper;

class Cookies
{
    public static function set($key, $value){
        $cookies = Yii::$app->response->cookies;
        $ck = new \yii\web\Cookie();
        $ck->name = $key;
        $ck->value = is_string($value) ? $value : json_encode(ArrayHelper::toArray($value));
        $ck->expire = time() + 60*60*24*30*12;
        $cookies->add($ck);
    }
    public static function mergeValue($key, $value){
        $old = Yii::$app->request->cookies->getValue($key);
        $cookies = Yii::$app->response->cookies;
        $ck = new \yii\web\Cookie();
        $ck->name = $key;
        $ck->value = is_string($value) ? $value : json_encode(ArrayHelper::toArray($value));
        $ck->expire = time() + 60*60*24*30*12;
        $cookies->add($ck);
    }
    public static function get($key){
        return Yii::$app->request->cookies->getValue($key);
    }
}