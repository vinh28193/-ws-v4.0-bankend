<?php

use yii\db\Migration;

/**
 * Class m190717_115903_alter_order_table
 */
class m190717_115903_alter_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','is_special',$this->integer(11)->defaultValue(0));
        $this->renameColumn('product','check_special','is_special');
        $this->alterColumn('product','is_special',$this->integer(11)->defaultValue(0)->comment('check hệ thống tự bắt hàng đặc biệt'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('product','is_special','check_special');
        $this->alterColumn('product','check_special',$this->integer()->comment('check hệ thống tự bắt hàng đặc biệt'));
        $this->dropColumn('order','is_special');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190717_115903_alter_order_table cannot be reverted.\n";

        return false;
    }
    */
}
