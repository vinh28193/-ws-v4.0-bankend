<?php

use yii\db\Migration;

/**
 * Class m190223_063600_updated_created_time_to_updated_created_at
 */
class m190223_063600_updated_created_time_to_updated_created_at extends Migration
{
    public function safeUp()
    {
        $data = [
            'order', 'coupon',
            'category', 'seller',
            'order_fee', 'package_item',
            'address', 'action_scope',
            'category_group', 'customer',
            'actions', 'scopes',
            'category_custom_policy', 'shipment_returned',
            'package', 'product',
            'scope_user', 'system_district',
            'system_state_province', 'warehouse',
            'wallet_transaction', 'shipment'
        ];
        $sql = "";
        foreach ($data as $v){
//            $sql .= "ALTER TABLE `".$v."`
//CHANGE COLUMN `created_time` `created_at` bigint(20) NULL DEFAULT NULL COMMENT 'Update qua behaviors tự động' ,
//CHANGE COLUMN `updated_time` `updated_at` bigint(20) NULL DEFAULT NULL COMMENT 'Update qua behaviors tự động';";
            $this->renameColumn($v,'created_time','created_at');
            $this->renameColumn($v,'updated_time','updated_at');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190223_060918_updated_created_time_to_updated_created_at cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_063600_updated_created_time_to_updated_created_at cannot be reverted.\n";

        return false;
    }
    */
}
