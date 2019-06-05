<?php

use yii\db\Migration;

/**
 * Class m190321_140024_add_version_field_Order_product_package_packetItem_tables
 */
class m190321_140024_add_version_field_Order_product_package_packetItem_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'version', $this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('product', 'version', $this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('order_fee', 'version', $this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('package_item', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('package', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('manifest', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('coupon', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('category', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('category_custom_policy', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('category_group', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('shipment', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('shipment_returned', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('store', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('store_additional_fee', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('system_country', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('system_currency', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('system_district', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('system_district_mapping', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('system_state_province', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('warehouse', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('tracking_code', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('address', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('country', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        //$this->addColumn('currency', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('customer', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));
        $this->addColumn('seller', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190321_140024_add_version_field_Order_product_package_packetItem_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190321_140024_add_version_field_Order_product_package_packetItem_tables cannot be reverted.\n";

        return false;
    }
    */
}
