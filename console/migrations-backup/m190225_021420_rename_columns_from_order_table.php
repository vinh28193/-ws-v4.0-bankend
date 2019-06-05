<?php

use yii\db\Migration;

/**
 * Class m190225_021420_rename_columns_from_order_table
 */
class m190225_021420_rename_columns_from_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('order','total_price_amount_local','total_origin_fee_local');
        $this->renameColumn('order','total_tax_us_amount_local','total_origin_tax_fee_local');
        $this->renameColumn('order','total_shipping_us_amount_local','total_origin_shipping_fee_local');
        $this->renameColumn('order','total_weshop_fee_amount_local','total_weshop_fee_local');
        $this->renameColumn('order','total_intl_shipping_fee_amount_local','total_intl_shipping_fee_local');
        $this->renameColumn('order','total_delivery_fee_amount_local','total_delivery_fee_local');
        $this->renameColumn('order','total_packing_fee_amount_local','total_packing_fee_local');
        $this->renameColumn('order','total_inspection_fee_amount_local','total_inspection_fee_local');
        $this->renameColumn('order','total_insurance_fee_amount_local','total_insurance_fee_local');
        $this->renameColumn('order','total_vat_amount_local','total_vat_amount_local');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('order','total_origin_fee_local','total_price_amount_local');
        $this->renameColumn('order','total_origin_tax_fee_local','total_tax_us_amount_local');
        $this->renameColumn('order','total_origin_shipping_fee_local','total_shipping_us_amount_local');
        $this->renameColumn('order','total_weshop_fee_local','total_weshop_fee_amount_local');
        $this->renameColumn('order','total_intl_shipping_fee_local','total_intl_shipping_fee_amount_local');
        $this->renameColumn('order','total_delivery_fee_local','total_delivery_fee_amount_local');
        $this->renameColumn('order','total_packing_fee_local','total_packing_fee_amount_local');
        $this->renameColumn('order','total_inspection_fee_local','total_inspection_fee_amount_local');
        $this->renameColumn('order','total_insurance_fee_local','total_insurance_fee_amount_local');
        $this->renameColumn('order','total_vat_amount_local','total_vat_amount_local');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190225_021420_rename_columns_from_order_table cannot be reverted.\n";

        return false;
    }
    */
}
