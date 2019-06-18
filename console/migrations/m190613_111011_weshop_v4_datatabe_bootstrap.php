<?php

use yii\db\Migration;


/**
 * Class m190613_111011_weshop_v4_datatabe_bootstrap
 */
class m190613_111011_weshop_v4_datatabe_bootstrap extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS = 0;");
        $this->truncateTable('{{%store}}');
        //$this->alterColumn('{{%store}}', 'name', $this->string(255)->notnull()->after('country_name'));
        $this->alterColumn('{{%store}}', 'locale', $this->string(5)->notnull()->after('url'));
       // $this->addColumn('{{%store}}', 'symbol', $this->string(5)->notnull()->after('currency'));
       // $this->addColumn('{{%store}}', 'country_code', $this->string(5)->notnull()->after('country_id'));
        $this->batchInsert('{{%store}}',
            ['id', 'country_id', 'country_code', 'country_name', 'name', 'address', 'url', 'locale', 'currency', 'symbol', 'status', 'env', 'version'],
            [
                [1, 1, 'VN', 'Việt Nam', 'Weshop Viet Nam', '18 Tam Trinh, Hà Nội', 'weshop.com.vn', 'vi', 'VND', '₫', 1, 'prod', '4.0'],
                [2, 2, 'ID', 'Indonesia', 'Weshop Indonesia', '18 Tam Trinh, Hà Nội', 'weshop.co.id', 'id', 'IDR', 'Rp', 1, 'prod', '4.0'],
            ]
        );
        $this->truncateTable('{{%store_additional_fee}}');
        $this->batchInsert('{{%store_additional_fee}}',
            [
                'id', 'store_id', 'name', 'type', 'label', 'currency', 'description', 'condition_data', 'condition_description', 'status', 'created_by', 'created_time', 'updated_by', 'updated_time', 'fee_rate', 'version'
            ],
            [
                [1, 1, 'product_price', 'origin', 'Giá gốc', 'VND', 'Giá gốc sản phẩm', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [2, 1, 'tax_fee', 'origin', 'Phí Tax', 'VND', 'Phí tax', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [3, 1, 'shipping_fee', 'origin', 'Phí shipping tại xuất sứ', 'VND', 'Phí shipping tại xuất sứ', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [4, 1, 'purchase_fee', 'addition', 'Phí weshop', 'VND', 'Phí mua hộ', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [5, 1, 'international_shipping_fee', 'addition', 'Phí vận chuyển quốc tế', 'VND', 'Phí vận chuyển quốc tế', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [6, 1, 'custom_fee', 'addition', 'Phí Phụ Thu danh mục', 'VND', 'Phí phụ thu', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [7, 1, 'delivery_fee', 'addition', 'Phí vận chuyển nội địa', 'VND', 'Phí vận chuyển nội địa', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [8, 1, 'packing_fee', 'addition', 'Phí đóng hàng', 'VND', 'Phí đóng hàng', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [9, 1, 'inspection_fee', 'addition', 'Phí kiểm hàng', 'VND', 'Phí kiểm hàng', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [10, 1, 'insurance_fee', 'addition', 'Phí bảo hiểm', 'VND', 'Phí bảo hiểm', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [11, 1, 'vat_fee', 'addition', 'VAT ', 'VND', 'Phí VAT', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [35, 2, 'product_price', 'origin', 'Sell Price', 'IDR', 'Sell Price', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [36, 2, 'tax_fee', 'origin', 'Tax Fee', 'IDR', 'Tax Fee', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [37, 2, 'shipping_fee', 'origin', 'Shipping Fee', 'IDR', 'Shipping fee', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [38, 2, 'purchase_fee', 'addition', 'Purchase Fee', 'IDR', 'Purchase Fee', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [39, 2, 'international_shipping_fee', 'addition', 'International Shipping Fee', 'IDR', 'International Shipping Fee', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [40, 2, 'custom_fee', 'addition', 'Custom fee', 'IDR', 'Custom fee', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [41, 2, 'delivery_fee', 'addition', 'Delivery Fee Local', 'IDR', 'Delivery Fee Local', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [42, 2, 'packing_fee', 'addition', 'Packing Fee', 'IDR', 'Packing Fee', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [43, 2, 'inspection_fee', 'addition', 'Inspection Fee', 'IDR', 'Inspection Fee', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [44, 2, 'insurance_fee', 'addition', 'Insurance Fee', 'IDR', 'Insurance Fee', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
                [45, 7, 'vat_fee', 'addition', 'VAT', 'IDR', 'VAT', null, null, 1, 1, 1551150587, 1, 1551150587, 0.00, '4.0'],
            ]
        );

        $newCondition = [
            'purchase_fee' => [
                [
                    'conditions' => [
                        [
                            'value' => 'normal',
                            'key' => 'level',
                            'type' => 'string',
                            'operator' => '=='
                        ]
                    ],
                    'type' => 'P',
                    'value' => 10,
                    'unit' => 'price'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 'sliver',
                            'key' => 'level',
                            'type' => 'string',
                            'operator' => '=='
                        ]
                    ],
                    'type' => 'P',
                    'value' => 8,
                    'unit' => 'price'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 'gold',
                            'key' => 'level',
                            'type' => 'string',
                            'operator' => '=='
                        ]
                    ],
                    'type' => 'P',
                    'value' => 5,
                    'unit' => 'price'
                ],
            ]
        ];

        foreach ($newCondition as $name => $conditions) {
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
//        $this->addColumn('{{%category_group}}', 'is_special', $this->integer(11)->defaultValue(1)->after('parent_id'));
//        $this->addColumn('{{%category_group}}', 'special_min_amount', $this->integer(11)->defaultValue(0)->after('is_special'));
//        $this->addColumn('{{%category_group}}', 'custom_default_value', $this->integer(11)->defaultValue(0)->after('is_special'));
//        $this->truncateTable('{{%category_group}}');
        $category_group = require dirname(dirname(__DIR__)) . '\common\models\category_group.php';
        $inserts = [];
        foreach ($category_group as $array) {
            $rule = null;
            $deception = [];
            if ($array['condition_data'] !== null) {
                $rule = \yii\helpers\Json::encode($array['condition_data']);
                foreach ($array['condition_data'] as $condition) {
                    $calc = new \common\calculators\Calculator();
                    $calc->register($condition);
                    $deception[] = $calc->deception();
                }
            }
            $inserts[] = [
                'id' => $array['id'],
                'name' => $array['name'],
                'description' => $array['name'],
                'store_id' => 1,
                'parent_id' => null,
                'is_special' => $array['special'],
                'special_min_amount' => $array['special_min_amount'],
                'custom_default_value' => 0,
                'rule' => $rule,
                'rule_description' => !empty($deception) ? implode(', ', $deception) : null,
                'active' => 1,
                'created_at' => Yii::$app->getFormatter()->asTimestamp('now'),
                'updated_at' => Yii::$app->getFormatter()->asTimestamp('now'),
            ];
        }
        $this->batchInsert('{{%category_group}}', [
            'id', 'name', 'description', 'store_id', 'parent_id', 'is_special', 'special_min_amount', 'custom_default_value', 'rule', 'rule_description', 'active', 'created_at', 'updated_at'
        ], $inserts);
        $this->execute("SET FOREIGN_KEY_CHECKS = 1;");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS = 0;");

        $this->alterColumn('{{%store}}', 'locale', $this->string(5)->notnull()->after('country_id'));
        $this->dropColumn('{{%store}}', 'symbol');
        $this->dropColumn('{{%store}}', 'country_code');
        $this->dropColumn('{{%category_group}}', 'is_special');
        $this->dropColumn('{{%category_group}}', 'special_min_amount');
        $this->dropColumn('{{%category_group}}', 'custom_default_value');

        $this->execute("SET FOREIGN_KEY_CHECKS = 1;");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190613_111011_weshop_v4_datatabe_bootstrap cannot be reverted.\n";

        return false;
    }
    */
}
