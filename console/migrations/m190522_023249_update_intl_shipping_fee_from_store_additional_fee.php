<?php

use yii\db\Migration;

/**
 * Class m190522_023249_update_intl_shipping_fee_from_store_additional_fee
 */
class m190522_023249_update_intl_shipping_fee_from_store_additional_fee extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $rules = [
            'intl_shipping_fee' => [
                [
                    'conditions' => [
                        [
                            'value' => 'ebay',
                            'key' => 'portal',
                            'type' => 'string',
                            'operator' => '=='
                        ]
                    ],
                    'type' => 'F',
                    'value' => 12,
                    'unit' => 'weight'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 'amazon',
                            'key' => 'portal',
                            'type' => 'string',
                            'operator' => '=='
                        ]
                    ],
                    'type' => 'F',
                    'value' => 10,
                    'unit' => 'weight'
                ],
            ],
        ];

        foreach ($rules as $name => $conditions) {
            $dec = [];
            foreach ($conditions as $condition) {
                $calc = new \common\calculators\Calculator();
                $calc->register($condition);
                $dec[] = $calc->deception();
            }
            $dec = implode(", ", $dec);
            $this->update('store_additional_fee', [
                'condition_data' => json_encode($conditions),
                'condition_description' => $dec
            ], ['name' => $name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190522_023249_update_intl_shipping_fee_from_store_additional_fee cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190522_023249_update_intl_shipping_fee_from_store_additional_fee cannot be reverted.\n";

        return false;
    }
    */
}
