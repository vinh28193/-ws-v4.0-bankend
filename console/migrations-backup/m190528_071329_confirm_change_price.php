<?php

use yii\db\Migration;

/**
 * Class m190528_071329_confirm_change_price
 */
class m190528_071329_confirm_change_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','confirm_change_price',$this->integer()->comment('0: là không có thay đổi giá hoặc có thay đổi nhưng đã confirm. 1: là có thay đổi cần xác nhận'));
        $this->addColumn('product','confirm_change_price',$this->integer()->comment('0: là không có thay đổi giá hoặc có thay đổi nhưng đã confirm. 1: là có thay đổi cần xác nhận'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190528_071329_confirm_change_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190528_071329_confirm_change_price cannot be reverted.\n";

        return false;
    }
    */
}
