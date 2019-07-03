<?php

use yii\db\Migration;

/**
 * Class m190702_113329_update_note_update_payment_table_order
 */
class m190702_113329_update_note_update_payment_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','note_update_payment',$this->text()->null()->comment('note khi chỉnh sửa payment'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','note_update_payment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190702_113329_update_note_update_payment_table_order cannot be reverted.\n";

        return false;
    }
    */
}
