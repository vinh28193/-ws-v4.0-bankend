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
    public function createOrUpdate($validate = true) {
        /** @var self $mess */
        $mess = self::find()->where(['id' => $this->id, 'language' => $this->language])->one();
        if($mess){
            $mess->translation = $this->translation;
            return $mess->save($validate);
        }else{
            return $this->save();
        }
    }
}