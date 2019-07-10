<?php

use yii\db\Migration;

/**
 * Class m190710_072042_update_column_updated_at_table_payment_transaction
 */
class m190710_072042_update_column_updated_at_table_payment_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_transaction','updated_at',$this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_transaction','updated_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190710_072042_update_column_updated_at_table_payment_transaction cannot be reverted.\n";

        return false;
    }
    */
}
