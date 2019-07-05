<?php

use yii\db\Migration;

/**
 * Class m190705_081530_alter_payment_transaction
 */
class m190705_081530_alter_payment_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('payment_transaction','courier_name',$this->string(255)->null());
        $this->alterColumn('payment_transaction','service_code',$this->string(32)->null());
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
        echo "m190705_081530_alter_payment_transaction cannot be reverted.\n";

        return false;
    }
    */
}
