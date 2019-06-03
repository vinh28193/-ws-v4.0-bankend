<?php


namespace frontend\widgets\search;

use yii\helpers\Url;
use Yii;

class UrlHelper
{

    public static function createUrl($params)
    {
        return Url::current($params,true);
    }
}