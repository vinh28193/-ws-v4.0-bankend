<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-27
 * Time: 09:52
 */

namespace common\helpers;

use Yii;
use common\modelsMongo\ChatMongoWs;

class ChatHelper
{


    private static function resolveMessage($message)
    {
        return is_array($message) ? json_encode($message) : $message;
    }

    private static function createParam($message,$path, $type, $sources){
        $message = self::resolveMessage($message);
        $identity = self::getIdentity();
        return [

        ];
    }
    /**
     * @return null|\yii\web\IdentityInterface
     */
    private static function getIdentity()
    {
        return Yii::$app->getUser()->getIdentity();
    }

    /**
     * push a message
     * @param $message
     * @param $path
     * @param $type
     * @param $sources
     */
    public static function push($message, $path, $type, $sources)
    {
        $message = self::resolveMessage($message);
        $identity = self::getIdentity();
        $model = new ChatMongoWs;
    }

    /**
     * @param $path
     * @param $type
     * @param $sources
     */
    public static function pull($path,$type,$sources){

    }
    /**
     * string `sale: acbd assign to bin 12345 at 2019/03/27`
     * @param $activeRecord \yii\db\ActiveRecord
     * @param $action string
     * @return string;
     */
    public static function pushFromActiveRecord($activeRecord, $action = 'update')
    {
        $template = "{role}:{identity} $action {options} at {time}";
        return $template;
    }
}