<?php


namespace common\additional;


use common\components\StoreManager;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\components\InternationalShippingCalculator;
use common\calculators\CalculatorService;

class StoreAdditionalFee extends \common\models\StoreAdditionalFee
{
    const TYPE_ORIGIN = 'origin';
    const TYPE_LOCAL = 'local';
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
        /** @var  $storeManager  StoreManager */
        $storeManager = Yii::$app->storeManager;
        if ($this->name === 'custom_fee' && ($category = $additional->getCategory()) !== null) {
            $value = $category->getCustomFee($additional);
        }else if (($conditions = $this->getConditions()) !== false) {
            $value = CalculatorService::calculator($conditions, $additional);
        }

        return [$value, $storeManager->roundMoney($value * $storeManager->getExchangeRate())];

    }
}