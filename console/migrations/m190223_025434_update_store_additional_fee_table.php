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
        $conditions = [
            'origin_fee' => 'common\components\conditions\SimpleCondition',
            'origin_tax_fee' => 'common\components\conditions\OriginTaxCondition',
            'origin_shipping_fee' => 'common\components\conditions\OriginShippingFeeCondition',
            'weshop_fee' => 'common\components\conditions\StoreFeeCondition',
            'intl_shipping_fee' => 'common\components\conditions\InternationalShippingFeeCondition',
            'custom_fee' => 'common\components\conditions\CustomFeeCondition',
            'delivery_fee' => 'common\components\conditions\LocalDeliveryFeeCondition',

        ];
        foreach ($this->getStoreAdditionalFee() as $name => $storeAdditionalFee) {
            /** @var  $storeAdditionalFee 'common\models\StoreAdditionalFee */
            if (!isset($conditions[$name]) || ($condition = $conditions[$name]) === null) {
                $condition = 'common\components\conditions\SimpleCondition';
            }
            /** @var  $condition 'common\components\conditions\BaseCondition */
            $condition = new $condition;
            $storeAdditionalFee->setCondition($condition);
        }
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
