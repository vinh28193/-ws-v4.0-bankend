<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-07-11
 * Time: 15:20
 */

namespace common\i18n;


class Message extends \common\models\db\Message
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }
}