<?php

use yii\db\Migration;

/**
 * Class m190709_015315_update_column_staus_table_order
 */
class m190709_015315_update_column_staus_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'contacting', $this->bigInteger()->comment('time contacting'));
        $this->addColumn('order', 'awaiting_payment', $this->bigInteger()->comment('time chờ thanh toán'));
        $this->addColumn('order', 'awaiting_confirm_purchase', $this->bigInteger()->comment('time chờ mua hàng'));
        $this->addColumn('order', 'delivering', $this->bigInteger()->comment('time đang giao hàng'));
        $this->addColumn('order', 'delivered', $this->bigInteger()->comment('time đã giao hàng'));
        $this->addColumn('order', 'purchasing', $this->bigInteger()->comment('time đã giao hàng'));
        $this->addColumn('order', 'junk', $this->bigInteger()->comment('time đơn hàng rác'));
        $this->addColumn('order', 'refunded', $this->bigInteger()->comment('time hoàn trả lại tiền'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','contacting');
        $this->dropColumn('order','awaiting_payment');
        $this->dropColumn('order','awaiting_confirm_purchase');
        $this->dropColumn('order','delivering');
        $this->dropColumn('order','delivered');
        $this->dropColumn('order','purchasing');
        $this->dropColumn('order','junk');
        $this->dropColumn('order','refunded');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190709_015315_update_column_staus_table_order cannot be reverted.\n";

        return false;
    }
    */
}
