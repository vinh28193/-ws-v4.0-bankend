<?php

use yii\db\Migration;

/**
 * Class m190517_083226_add_column_transaction_code_into_order_table
 */
class m190517_083226_add_column_transaction_code_into_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'transaction_code', $this->string(32)->null()->after('payment_type'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'transaction_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190517_083226_add_column_transaction_code_into_order_table cannot be reverted.\n";

        return false;
    }
    */
}
