<?php

use yii\db\Migration;

/**
 * Class m190620_135238_alter_order_table
 */
class m190620_135238_alter_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('order', 'customer_id', $this->integer(11)->null());
        $this->alterColumn('order', 'receiver_email', $this->string(255)->null());
        $this->addColumn('order', 'courier_service', $this->string(32)->null());
        $this->addColumn('order', 'courier_name', $this->string(255)->null());
        $this->addColumn('order', 'courier_delivery_time', $this->string(255)->null());
        $this->addColumn('order', 'buyer_phone', $this->string(255)->notNull());
//        $this->dropForeignKey('fk-order-customer_id','order');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('order', 'customer_id', $this->integer(11)->notNull());
        $this->alterColumn('order', 'receiver_email', $this->string(255)->notNull());
        $this->dropColumn('order', 'courier_service');
        $this->dropColumn('order', 'courier_name');
        $this->dropColumn('order', 'courier_delivery_time');
        $this->dropColumn('order', 'buyer_phone');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190620_135238_alter_order_table cannot be reverted.\n";

        return false;
    }
    */
}
