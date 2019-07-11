<?php


namespace api\modules\v1\models;

use common\components\ThirdPartyLogs;
use Yii;
use yii\db\Exception;

class Order extends \common\models\Order
{

    public static function updateRecord($attributes, $conditions = [])
    {
        if (empty($attributes)) {
            return false;
        }
        $transaction = self::getDb()->beginTransaction();
        try {
            ThirdPartyLogs::setLog('Order', 'update', 'Upload form excel', $conditions, $attributes);
            $executed = self::updateAll($attributes, $conditions);
            $transaction->commit();
            return $executed > 0;
        } catch (Exception $exception) {
            Yii::error($exception);
            $transaction->rollBack();
            return false;
        }
    }
}