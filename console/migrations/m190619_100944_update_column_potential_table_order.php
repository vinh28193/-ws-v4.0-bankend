<?php

use yii\db\Migration;

/**
 * Class m190619_100944_update_column_potential_table_order
 */
class m190619_100944_update_column_potential_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'potential' , $this->integer(2)->comment("0 là khách hàng binh thường, 1 là khách hàng tiềm năng"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190619_100944_update_column_potential_table_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190619_100944_update_column_potential_table_order cannot be reverted.\n";

        return false;
    }
    */
}
