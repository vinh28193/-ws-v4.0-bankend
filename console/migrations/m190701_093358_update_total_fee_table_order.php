<?php

use yii\db\Migration;

/**
 * Class m190701_093358_update_total_fee_table_order
 */
class m190701_093358_update_total_fee_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','total_origin_shipping_fee_amount',$this->integer());
        $this->addColumn('order','total_vat_amount_amount',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','total_origin_shipping_fee_amount');
        $this->dropColumn('order','total_vat_amount_amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190701_093358_update_total_fee_table_order cannot be reverted.\n";

        return false;
    }
    */
}
