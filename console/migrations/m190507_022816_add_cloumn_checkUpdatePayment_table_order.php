<?php

use yii\db\Migration;

/**
 * Class m190507_022816_add_cloumn_checkUpdatePayment_table_order
 */
class m190507_022816_add_cloumn_checkUpdatePayment_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'check_update_payment',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190507_022816_add_cloumn_checkUpdatePayment_table_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190507_022816_add_cloumn_checkUpdatePayment_table_order cannot be reverted.\n";

        return false;
    }
    */
}
