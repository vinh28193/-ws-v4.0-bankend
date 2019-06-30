<?php

use yii\db\Migration;

/**
 * Class m190630_045533_update_check_packing_wood_add_table_order
 */
class m190630_045533_update_check_packing_wood_add_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','check_packing_wood',$this->integer(2)->comment('1 là có đóng gỗ, 0 là không đóng gỗ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','check_packing_wood');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190630_045533_update_check_packing_wood_add_table_order cannot be reverted.\n";

        return false;
    }
    */
}
