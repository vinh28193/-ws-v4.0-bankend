<?php

use yii\db\Migration;

/**
 * Class m190517_100537_create_top_up_transaction_code_into_payment_transaction
 */
class m190517_100537_create_top_up_transaction_code_into_payment_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_transaction', 'topup_transaction_code', $this->string(32)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_transaction', 'topup_transaction_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190517_100537_create_top_up_transaction_code_into_payment_transaction cannot be reverted.\n";

        return false;
    }
    */
}
