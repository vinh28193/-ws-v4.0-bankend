<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2018-08-23
 * Time: 14:13
 */

namespace common\helpers;

use Yii;
use yii\helpers\ArrayHelper;

class CustomerHelper
{

    /**
     * @param $identity \yii\web\IdentityInterface
     * @return bool
     */
    public static function isSystemAccount($identity)
    {
        if (method_exists($identity, 'isSystemAccount')) {
            return $identity->isSystemAccount();
        }
        return ArrayHelper::isIn($identity->getId(), [1, 2, 3]);
    }

    /**
     * @return string
     */
    public static function generateVerifyCode()
    {
        return (string)rand(10000, 99999);
    }

    public static function filterIdentity($identity = null)
    {

        if ($identity === null) {
            Yii::error(' opps null value');
            return null;
        } else if (strlen($identity) === 1 && (boolean)$identity === true ) {
            Yii::error(Yii::$app->getUser()->getIdentity());
            return Yii::$app->getUser()->getId();
        } else {
            Yii::error(' default');
            return $identity;
        }
    }
}