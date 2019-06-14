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

    const TYPE_ORIGIN = 'origin';
    const TYPE_ADDITION = 'addition';
    const TYPE_DISCOUNT = 'discount';

    /**
     * @return bool|array
     */
    public function getConditions()
    {
        $data = $this->condition_data;
        if ($data === null) {
            return false;
        }
        return Json::decode($data, true);
    }

    public function executeCondition(AdditionalFeeInterface $additional)
    {
        $value = 0;
        if ($this->name === 'custom_fee' && ($category = $additional->getCategory()) !== null) {
            $value = $category->getCustomFee($additional);
        } else if (($conditions = $this->getConditions()) !== false) {
            $value = CalculatorService::calculator($conditions, $additional);
        }
        $storeManager =  Yii::$app->storeManager;
        return [$value, $storeManager->roundMoney($value * $storeManager->getExchangeRate())];

    }
}