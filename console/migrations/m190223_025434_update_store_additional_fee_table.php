<?php

use yii\db\Migration;

/**
 * Class m190223_025434_update_store_additional_fee_table
 */
class m190223_025434_update_store_additional_fee_table extends Migration
{
    use \common\components\StoreAdditionalFeeRegisterTrait;

    public function safeUp()
    {
//        $conditions = [
//            'total_final_amount_local' => 'common\components\conditions\ExchangeRateCondition',
//            'total_paid_amount_local' => 'common\components\conditions\SimpleCondition',
//            'total_refund_amount_local' => 'common\components\conditions\SimpleCondition',
//            'total_amount_local' => 'common\components\conditions\SimpleCondition',
//            'total_fee_amount_local' => 'common\components\conditions\SimpleCondition',
//            'total_counpon_amount_local' => 'common\components\conditions\SimpleCondition',
//            'total_promotion_amount_local' => 'common\components\conditions\SimpleCondition',
//            'total_price_amount_local' => 'common\components\conditions\ExchangeRateCondition',
//            'total_tax_us_amount_local' => 'common\components\conditions\OriginTaxCondition',
//            'total_shipping_us_amount_local' => 'common\components\conditions\OriginShippingFeeCondition',
//            'total_weshop_fee_amount_local' => 'common\components\conditions\StoreFeeCondition',
//            'total_intl_shipping_fee_amount_local' => 'common\components\conditions\InternationalShippingFeeCondition',
//            'total_custom_fee_amount_local' => 'common\components\conditions\CustomFeeCondition',
//            'total_delivery_fee_amount_local' => 'common\components\conditions\LocalDeliveryFeeCondition',
//
//        ];
//        foreach ($this->getStoreAdditionalFee() as $name => $storeAdditionalFee) {
//            /** @var  $storeAdditionalFee 'common\models\StoreAdditionalFee */
//            if (!isset($conditions[$name]) || ($condition = $conditions[$name]) === null) {
//                $condition = 'common\components\conditions\SimpleCondition';
//            }
//            /** @var  $condition 'common\components\conditions\BaseCondition */
//            $condition = new $condition;
//            $storeAdditionalFee->setCondition($condition);
//        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190222_040800_update_store_additional_fee_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_025434_update_store_additional_fee_table cannot be reverted.\n";

        return false;
    }
    */
}
