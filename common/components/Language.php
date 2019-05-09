<?php
/**
 * Created by IntelliJ IDEA.
 * User: Thaim
 * Date: 5/8/2018
 * Time: 1:44 PM
 */

namespace common\components;

use Yii;
use common\models\db\Message;
use common\models\db\SourceMessage;

class Language
{
    /**
     * @param $key
     * @param $defaultVal
     * @param array $params
     * @param string $cate
     * @return string
     */
    static function t($key, $defaultVal, $params = [], $cate = 'wallet')
    {
        $rs = Yii::t($cate, $key, $params);
        $placeholders = [];
        foreach ((array) $params as $name => $value) {
            $placeholders['{' . $name . '}'] = $value;
        }

        $checkKey = ($placeholders === []) ? $key : strtr($key, $placeholders);
        $checkDefaultValue= ($placeholders === []) ? $defaultVal : strtr($defaultVal, $placeholders);

        if ($rs === $checkKey && $rs != $checkDefaultValue) {
            $sourceMessage = new SourceMessage();
            $sourceMessage->category = $cate;
            $sourceMessage->message = $key;
            if ($sourceMessage->save(false)) {
                $msg = new Message();
                $msg->id = $sourceMessage->id;
                $msg->language = Yii::$app->language;
                $msg->translation = $defaultVal;
                $msg->save(false);
            }
            $key = [
                'yii\i18n\DbMessageSource',
                $cate,
                Yii::$app->language
            ];
            Yii::$app->cache->delete($key);
            return $defaultVal;
        }
        return $rs;
    }
}