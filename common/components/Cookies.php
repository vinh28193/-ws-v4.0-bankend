<?php


namespace common\components;


use Yii;

class Cookies
{
    public static function set($key, $value){
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => $key,
            'value' => $value,
            'expire' => 60*60*24*30*12,
        ]));
    }
    public static function mergeValue($key, $value){
        $old = Yii::$app->request->cookies->getValue($key);
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => $key,
            'value' => array_merge($old,$value),
            'expire' => 60*60*24*30*12,
        ]));
    }
    public static function get($key){
        return Yii::$app->request->cookies->getValue($key);
    }
}