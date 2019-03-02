<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 01/03/2019
 * Time: 16:18
 */

namespace common\models;


use Yii;

class Scopes extends \common\models\db\Scopes
{
    const SCOPES_FOR_CUSTOMER = "Scopes_for_customer";
    const SCOPES_FOR_USER_ID = "Scopes_for_user_id_";

    public static function removeCacheScope($key,$user_id = 1){
        Yii::$app->cache->delete($key);
        Yii::$app->cache->delete(Scopes::SCOPES_FOR_CUSTOMER);
        Yii::$app->cache->delete(Scopes::SCOPES_FOR_USER_ID.$user_id);
    }
}