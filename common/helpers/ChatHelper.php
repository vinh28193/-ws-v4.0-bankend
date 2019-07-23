<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-03-27
 * Time: 09:52
 */

namespace common\helpers;

use common\modelsMongo\ChatMongoWs;
use Yii;

class ChatHelper
{


    /**
     * @param $message
     * @return string
     */
    private static function resolveMessage($message)
    {
        return is_array($message) ? json_encode($message) : $message;
    }

    /**
     * @param $message
     * @param $path
     * @param $type
     * @param $source
     * @return array
     */
    private static function createParam($message, $path, $type, $source, $image)
    {
        $message = self::resolveMessage($message);
        $identity = self::getPublicIdentity();
        return [
            'success' => true,
            'message' => $message,
            'date' => Yii::$app->formatter->asDatetime('now'),
            'user_id' => $identity ? $identity['id'] : null,
            'user_email' => $identity ? $identity['email'] : null,
            'user_name' => $identity ? $identity['username'] : null,
            'user_app' => 'weshop 2019',
            'user_request_suorce' => $source,
            'request_ip' => Yii::$app->getRequest()->getUserIP(),
            'user_avatars' => null,
            'Order_path' => $path,
            'is_send_email_to_customer' => null,
            'type_chat' => $type,
            'link_image' => $image,
            'is_customer_vew' => null,
            'is_employee_vew' => null,
        ];
    }

    /**
     * @return array
     */
    private static function getPublicIdentity()
    {
        if(!($app =  Yii::$app) instanceof \yii\web\Application){
            return null;
        }
        if (($user = $app->getUser()->getIdentity()) !== null && $user instanceof \common\components\UserPublicIdentityInterface) {
            return $user->getPublicIdentity();
        }
        return null;
    }

    /**
     * @param $message
     * @param $path
     * @param $type
     * @param $source
     * @return bool
     */
    public static function push($message, $path, $type, $source, $image)
    {
        $model = new ChatMongoWs;
        $model->load(self::createParam($message, $path, $type, $source, $image), '');
        return $model->save();

    }

    /**
     * @param $path
     * @param $type
     * @param $sources
     */
    public static function pull($path, $type, $sources)
    {
        return ChatMongoWs::find()->where(['and', ['Order_path' => $path], ['type_chat' => $type], ['user_request_suorce' => $sources]])->all();
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
//        return self::push($template,$activeRecord->id);
    }
}