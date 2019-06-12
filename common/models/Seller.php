<?php

namespace common\models;

use Yii;


class Seller extends \common\models\db\Seller
{

    public function safeCreate()
    {
        if ($this->isNewRecord === false) {
            return false;
        }
        if (($sellerSafe = self::find()->where(['AND', ['seller_name' => $this->seller_name], ['portal' => $this->portal]])->one()) === null) {
            $this->save(false);
            return $this;
        }
        return $sellerSafe;
    }
}
