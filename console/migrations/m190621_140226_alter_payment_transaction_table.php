<?php

use yii\db\Migration;

/**
 * Class m190621_140226_alter_payment_transaction_table
 */
class m190621_140226_alter_payment_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('payment_transaction','carts',$this->string(255)->null());
        $this->alterColumn('payment_transaction','courier_name',$this->string(255)->null());
        $this->alterColumn('payment_transaction','service_code',$this->string(32)->null());
        $this->alterColumn('payment_transaction','international_shipping_fee',$this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190621_140226_alter_payment_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
