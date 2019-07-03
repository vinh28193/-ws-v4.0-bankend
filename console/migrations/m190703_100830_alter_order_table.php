<?php

use yii\db\Migration;

/**
 * Class m190703_100830_alter_order_table
 */
class m190703_100830_alter_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'payment_provider', $this->string(255)->null());
        $this->addColumn('order', 'payment_method', $this->string(255)->null());
        $this->addColumn('order', 'payment_bank', $this->string(255)->null());
        $this->addColumn('order', 'payment_transaction_code', $this->string(32)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'payment_provider');
        $this->dropColumn('order', 'payment_method');
        $this->dropColumn('order', 'payment_bank');
        $this->dropColumn('order', 'payment_transaction_code');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190703_100830_alter_order_table cannot be reverted.\n";

        return false;
    }
    */
}
