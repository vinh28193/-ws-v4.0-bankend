<?php

use yii\db\Migration;

/**
 * Class m190524_063537_add_parent_transaction_code_column_payment_transaction
 */
class m190524_063537_add_parent_transaction_code_column_payment_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_transaction', 'parent_transaction_code', $this->string(32)->null()->after('topup_transaction_code'));
        $this->addColumn('payment_transaction', 'order_code', $this->string(32)->null()->after('parent_transaction_code'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropColumn('payment_transaction', 'order_code');
        $this->dropColumn('payment_transaction', 'parent_transaction_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190524_063537_add_parent_transaction_code_column_payment_transaction cannot be reverted.\n";

        return false;
    }
    */
}
