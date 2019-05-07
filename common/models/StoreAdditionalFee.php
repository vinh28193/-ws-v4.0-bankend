<?php
/**
 * Created by PhpStorm.
 * User: vinhs
 * Date: 2019-02-23
 * Time: 09:24
 */

namespace common\models;

use Yii;
use yii\helpers\Json;
use common\calculators\CalculatorService;
use common\components\AdditionalFeeInterface;
use common\models\db\StoreAdditionalFee as DbStoreAdditionalFee;

class StoreAdditionalFee extends DbStoreAdditionalFee
{
    /**
     * @return array
     */
    public function getCondition()
    {
        $data = $this->condition_data;
        if ($data === null) {
            return [];
        }
        return Json::decode($data, true);
    }

    public function executeCondition(AdditionalFeeInterface $additional)
    {

        if ($this->name === 'custom_fee') {
            if (($category = $additional->getCustomCategory()) !== null) {
                $value = $category->getCustomFee($additional);
            } else {
                $value = 0.0;
            }
        } else {
            $condition = $condition = $this->getCondition();
            if (empty($condition)) {
                return false;
            }
            $value = CalculatorService::calculator($condition, $additional);
        }

        return [$value, Yii::$app->storeManager->roundMoney($value * $additional->getExchangeRate())];

    }
}