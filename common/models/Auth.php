<?php

namespace common\models;

use Yii;

class Auth extends \common\models\db\Auth
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
