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
        ]));
    }
    public static function get($key){
        return Yii::$app->request->cookies->getValue($key);
    }
}