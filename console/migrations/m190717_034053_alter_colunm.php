<?php

use yii\db\Migration;

/**
 * Class m190717_034053_alter_colunm
 */
class m190717_034053_alter_colunm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('payment_transaction','payment_method',$this->string(50)->null());
        $this->alterColumn('payment_transaction','payment_provider',$this->string(50)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('payment_transaction','payment_method',$this->string(50)->notNull());
        $this->alterColumn('payment_transaction','payment_provider',$this->string(50)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190717_034053_alter_colunm cannot be reverted.\n";

        return false;
    }
    */
}
