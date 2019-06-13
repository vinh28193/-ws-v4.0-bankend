<?php

use yii\db\Migration;

/**
 * Class m190613_075823_update_payment_transaction_table
 */
class m190613_075823_update_payment_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('payment_transaction','shipping',$this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('payment_transaction','shipping',$this->integer(11)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190613_075823_update_payment_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
