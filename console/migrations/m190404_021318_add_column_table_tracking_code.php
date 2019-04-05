<?php

use yii\db\Migration;

/**
 * Class m190404_021318_add_column_table_tracking_code
 */
class m190404_021318_add_column_table_tracking_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tracking_code','status_merge',"varchar(255) null comment 'Trạng thái của tracking với việc đối chiếu tracking với bảng ext'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190404_021318_add_column_table_tracking_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190404_021318_add_column_table_tracking_code cannot be reverted.\n";

        return false;
    }
    */
}
