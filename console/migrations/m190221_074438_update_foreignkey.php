<?php

use yii\db\Migration;

/**
 * Class m190221_074438_update_foreignkey
 */
class m190221_074438_update_foreignkey extends Migration
{
    public $list = [
            'order' => [
                [
                    'column' => 'store_id',
                    'table' => 'store',
                ],
                [
                    'column' => 'customer_id',
                    'table' => 'customer',
                ],
                [
                    'column' => 'receiver_country_id',
                    'table' => 'system_country',
                ],
                [
                    'column' => 'receiver_province_id',
                    'table' => 'system_state_province',
                ],
                [
                    'column' => 'receiver_district_id',
                    'table' => 'system_district',
                ],
                [
                    'column' => 'receiver_address_id',
                    'table' => 'address',
                ],
                [
                    'column' => 'sale_support_id',
                    'table' => 'user',
                ],
                [
                    'column' => 'seller_id',
                    'table' => 'seller',
                ],
                [
                    'column' => 'coupon_id',
                    'table' => 'coupon',
                    'is_not_fk' => 1
                ],
                [
                    'column' => 'promotion_id',
                    'table' => 'coupon',
                    'is_not_fk' => 1
                ],
            ],
            'order_fee' => [
                [
                    'column' => 'order_id',
                    'table' => 'order',
                ],
            ],
            'product' => [
                [
                    'column' => 'order_id',
                    'table' => 'order',
                ],
                [
                    'column' => 'seller_id',
                    'table' => 'seller',
                ],
                [
                    'column' => 'category_id',
                    'table' => 'category',
                ],
                [
                    'column' => 'custom_category_id',
                    'table' => 'category_custom_policy',
                ]
            ],
            'wallet_transaction' => [
                [
                    'column' => 'order_id',
                    'table' => 'order',
                ],
                [
                    'column' => 'customer_id',
                    'table' => 'customer',
                ]
            ],
            'package' => [
                [
                    'column' => 'warehouse_id',
                    'table' => 'warehouse',
                ]
            ],
            'package_item' => [
                [
                    'column' => 'package_id',
                    'table' => 'package',
                ],
                [
                    'column' => 'order_id',
                    'table' => 'order',
                ]
            ],
            'shipment' => [
                [
                    'column' => 'receiver_country_id',
                    'table' => 'system_country',
                ],
                [
                    'column' => 'warehouse_send_id',
                    'table' => 'warehouse',
                ],
                [
                    'column' => 'receiver_address_id',
                    'table' => 'address',
                ],
                [
                    'column' => 'receiver_province_id',
                    'table' => 'system_state_province',
                ],
                [
                    'column' => 'receiver_district_id',
                    'table' => 'system_district',
                ],
                [
                    'column' => 'customer_id',
                    'table' => 'customer',
                ],
            ],
            'shipment_returned' => [
                [
                    'column' => 'warehouse_send_id',
                    'table' => 'warehouse',
                ],
                [
                    'column' => 'customer_id',
                    'table' => 'customer',
                ],
                [
                    'column' => 'shipment_id',
                    'table' => 'shipment',
                ]
            ],
            'store' => [
                [
                    'column' => 'country_id',
                    'table' => 'system_country',
                ],
                [
                    'column' => 'currency_id',
                    'table' => 'system_currency',
                ]
            ],
            'customer' => [
                [
                    'column' => 'store_id',
                    'table' => 'store',
                ]
            ],
            'system_state_province' => [
                [
                    'column' => 'country_id',
                    'table' => 'system_country',
                ]
            ],
            'system_district' => [
                [
                    'column' => 'province_id',
                    'table' => 'system_state_province',
                ],
                [
                    'column' => 'country_id',
                    'table' => 'system_country',
                ]
            ],
            'address' => [
                [
                    'column' => 'country_id',
                    'table' => 'system_country',
                ],
                [
                    'column' => 'province_id',
                    'table' => 'system_state_province',
                ],
                [
                    'column' => 'district_id',
                    'table' => 'system_district',
                ],
                [
                    'column' => 'store_id',
                    'table' => 'store',
                ],
                [
                    'column' => 'customer_id',
                    'table' => 'customer',
                ]
            ],
        /*
            'action_scope' => [
                [
                    'column' => 'action_id',
                    'table' => 'actions',
                ],
                [
                    'column' => 'scope_id',
                    'table' => 'scopes',
                ]
            ],
            'scope_user' => [
                [
                    'column' => 'user_id',
                    'table' => 'user',
                ],
                [
                    'column' => 'scope_id',
                    'table' => 'scopes',
                ]
            ],
          */
            'coupon' => [
                [
                    'column' => 'store_id',
                    'table' => 'store',
                ],
                [
                    'column' => 'created_by',
                    'table' => 'user',
                ]
            ],
            'category_custom_policy' => [
                [
                    'column' => 'store_id',
                    'table' => 'store',
                ]
            ],
            'warehouse' => [
                [
                    'column' => 'district_id',
                    'table' => 'system_district',
                ],
                [
                    'column' => 'province_id',
                    'table' => 'system_state_province',
                ],
                [
                    'column' => 'country_id',
                    'table' => 'system_country',
                ],
                [
                    'column' => 'store_id',
                    'table' => 'store',
                ]
            ],
        ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // FK for order table
        foreach ($this->list as $key => $list_data){
            foreach ($list_data as $data){
                //$this->createIndex('idx-'.$key.'-'.$data['column'],$key,$data['column']);
                if(!isset($data['is_not_fk'])){
                    $this->addForeignKey('fk-'.$key.'-'.$data['column'], $key, $data['column'], $data['table'], 'id');
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190221_074438_update_foreignkey safeDown forech list .\n";
        foreach ($this->list as $key => $list_data){
            foreach ($list_data as $data){
                $this->dropIndex('idx-'.$key.'-'.$data['column'],$key);
                if(!isset($data['is_not_fk'])){
                    $this->dropForeignKey('fk-'.$key.'-'.$data['column'], $key);
                }
                $this->dropTable($key); //delete tables
            }
        }
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_074438_update_foreignkey cannot be reverted.\n";

        return false;
    }
    */
}
