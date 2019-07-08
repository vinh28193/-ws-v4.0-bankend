<?php

use yii\db\Migration;

/**
 * Class m190708_014000_edit_column_total_custom_fee_amount_table_order
 */
class m190708_014000_edit_column_total_custom_fee_amount_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('order', 'total_custom_fee_amount_amount', 'total_custom_fee_amount');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','total_custom_fee_amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190708_014000_edit_column_total_custom_fee_amount_table_order cannot be reverted.\n";

        return false;
    }
    */
}
