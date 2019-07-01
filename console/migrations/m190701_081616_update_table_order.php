<?php

use yii\db\Migration;

/**
 * Class m190701_081616_update_table_order
 */
class m190701_081616_update_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','total_intl_shipping_fee_amount',$this->integer());
        $this->addColumn('order','total_origin_tax_fee_amount',$this->integer());
        $this->addColumn('order','total_weshop_fee_amount',$this->integer());
        $this->addColumn('order','total_boxed_fee_amount',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','total_intl_shipping_fee_amount');
        $this->dropColumn('order','total_origin_tax_fee_amount');
        $this->dropColumn('order','total_weshop_fee_amount');
        $this->dropColumn('order','total_boxed_fee_amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190701_081616_update_table_order cannot be reverted.\n";

        return false;
    }
    */
}
