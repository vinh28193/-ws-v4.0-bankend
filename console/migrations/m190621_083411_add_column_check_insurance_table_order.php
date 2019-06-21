<?php

use yii\db\Migration;

/**
 * Class m190621_083411_udd_column_check_insurance_table_order
 */
class m190621_083411_add_column_check_insurance_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'check_insurance', $this->integer(2)->comment("0 - không chọn bảo hiểm; 1- Có chọn bảo hiểm"));
        $this->addColumn('order', 'check_inspection', $this->integer(2)->comment("0 - không kiểm hàng; 1- Có kiểm hàng"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'check_insurance');
        $this->dropColumn('order', 'check_inspection');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190621_083411_udd_column_check_insurance_table_order cannot be reverted.\n";

        return false;
    }
    */
}
