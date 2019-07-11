<?php

use yii\db\Migration;

/**
 * Class m190711_104731_update_column_checkSpecial_table_product
 */
class m190711_104731_update_column_checkSpecial_table_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','check_special',$this->integer()->comment('check hệ thống tự bắt hàng đặc biệt'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product','check_special');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190711_104731_update_column_checkSpecial_table_product cannot be reverted.\n";

        return false;
    }
    */
}
