<?php

use yii\db\Migration;

/**
 * Class m190517_065155_revert_orders_from_payment_transaction
 */
class m190517_065155_revert_orders_from_payment_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_transaction','carts',$this->text()->notNull()->comment('list cart')->after('bulk_point'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_transaction','carts');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190517_065155_revert_orders_from_payment_transaction cannot be reverted.\n";

        return false;
    }
    */
}
