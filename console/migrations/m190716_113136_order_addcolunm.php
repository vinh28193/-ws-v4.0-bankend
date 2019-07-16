<?php

use yii\db\Migration;

/**
 * Class m190716_113136_order_addcolunm
 */
class m190716_113136_order_addcolunm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','transfer_to',$this->string()->comment('Order code của order nhận được số tiền chuyển'));
        $this->addColumn('order','refund_transfer',$this->integer()->comment('Thời gian chuyển'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','transfer_to');
        $this->dropColumn('order','refund_transfer');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190716_113136_order_addcolunm cannot be reverted.\n";

        return false;
    }
    */
}
