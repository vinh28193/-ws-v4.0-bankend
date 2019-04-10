<?php

use yii\db\Migration;

/**
 * Class m190408_093846_alter_store_additional_fee_table
 */
class m190408_093846_alter_store_additional_fee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('store_additional_fee', 'condition_name');
        $this->dropColumn('store_additional_fee', 'is_convert');
        $this->dropColumn('store_additional_fee', 'is_read_only');
        $rules = [
            'weshop_fee' => [
                [
                    'conditions' => [
                        [
                            'value' => 450,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '<'
                        ],
                        [
                            'value' => 'ebay',
                            'key' => 'portal',
                            'type' => 'string',
                            'operator' => '=='
                        ]
                    ],
                    'type' => 'P',
                    'value' => 12,
                    'unit' => 'price'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 450,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '<'
                        ],
                        [
                            'value' => 'amazon',
                            'key' => 'portal',
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
                            'value' => 450,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '<'
                        ],
                        [
                            'value' => 'other',
                            'key' => 'portal',
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
                            'value' => 450,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '>='
                        ],
                        [
                            'value' => 750,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '<'
                        ]
                    ],
                    'type' => 'P',
                    'value' => 10,
                    'unit' => 'price'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 750,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '>='
                        ],
                        [
                            'value' => 1000,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '<'
                        ]
                    ],
                    'type' => 'P',
                    'value' => 9,
                    'unit' => 'price'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 1000,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '>='
                        ],
                        [
                            'value' => 1500,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '<'
                        ]
                    ],
                    'type' => 'P',
                    'value' => 8.5,
                    'unit' => 'price'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 1500,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '>='
                        ],
                        [
                            'value' => 2000,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '<'
                        ]
                    ],
                    'type' => 'P',
                    'value' => 8,
                    'unit' => 'price'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 2000,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '>='
                        ],
                        [
                            'value' => 2500,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '<'
                        ]
                    ],
                    'type' => 'P',
                    'value' => 7,
                    'unit' => 'price'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 2500,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '>='
                        ],
                        [
                            'value' => 3000,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '<'
                        ]
                    ],
                    'type' => 'P',
                    'value' => 6,
                    'unit' => 'price'
                ],
                [
                    'conditions' => [
                        [
                            'value' => 2500,
                            'key' => 'price',
                            'type' => 'int',
                            'operator' => '>='
                        ],
                    ],
                    'type' => 'P',
                    'value' => 5,
                    'unit' => 'price'
                ],
            ],
            'intl_shipping_fee' => [
                [
                    'conditions' => [
                        [
                            'value' => 0,
                            'key' => 'weight',
                            'type' => 'int',
                            'operator' => '>'
                        ],
                    ],
                    'type' => 'F',
                    'value' => 10,
                    'unit' => 'weight'
                ],
            ],
            'delivery_fee_local' => [
                [
                    'conditions' => [
                        [
                            'value' => 0,
                            'key' => 'quantity',
                            'type' => 'int',
                            'operator' => '>'
                        ],
                    ],
                    'type' => 'F',
                    'value' => 2,
                    'unit' => 'quantity'
                ],
            ]
        ];
        $this->update('store_additional_fee', ['condition_data' => null, 'condition_description' => null]);

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
        $this->addColumn('store_additional_fee', 'condition_name', $this->string(255)->null()->after('description')->comment('Fee Name'));
        $this->addColumn('store_additional_fee', 'is_convert', $this->smallInteger()->defaultValue(1)->comment('Is Convert (1:Can Convert;2:Can Not)'));
        $this->addColumn('store_additional_fee', 'is_read_only', $this->smallInteger()->defaultValue(1)->comment('Is Read Only'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190408_093846_alter_store_additional_fee_table cannot be reverted.\n";

        return false;
    }
    */
}
