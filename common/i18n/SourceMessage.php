<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-11
 * Time: 15:19
 */

namespace common\i18n;


class SourceMessage extends \common\models\db\SourceMessage
{

    public function getCacheKey($language){
        return [
            'yii\i18n\DbMessageSource',
            $this->category,
            $language
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id']);
    }
}