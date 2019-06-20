<?php

use yii\db\Migration;

/**
 * Class m190618_080525_update_payment_transaction_table
 */
class m190618_080525_update_payment_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // @Vinh them Migrate thua
        $this->addColumn('payment_transaction', 'courier_name', $this->string(255)->notNull());
        $this->addColumn('payment_transaction', 'service_code', $this->string(32)->notNull());
        $this->addColumn('payment_transaction', 'international_shipping_fee', $this->integer(11)->notNull());
        $this->addColumn('payment_transaction', 'insurance_fee', $this->integer(11)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_transaction', 'courier_name');
        $this->dropColumn('payment_transaction', 'service_code');
        $this->dropColumn('payment_transaction', 'international_shipping_fee');
        $this->dropColumn('payment_transaction', 'insurance_fee');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190618_080525_update_payment_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
