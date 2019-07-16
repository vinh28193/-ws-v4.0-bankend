<?php

use yii\db\Migration;

/**
 * Class m190716_120814_alter_colunm_tran
 */
class m190716_120814_alter_colunm_tran extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('payment_transaction' , 'transaction_type',$this->string(32));
        $this->alterColumn('payment_transaction' , 'payment_type',$this->string(32));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190716_120814_alter_colunm_tran cannot be reverted.\n";

        return false;
    }
    */
}
