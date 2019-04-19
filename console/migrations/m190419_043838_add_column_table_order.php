<?php

use yii\db\Migration;

/**
 * Class m190419_043838_add_column_table_order
 */
class m190419_043838_add_column_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','supported','bigint(20) NULL');
        $this->addColumn('order','ready_purchase','bigint(20) NULL');
        $this->addColumn('order','supporting','bigint(20) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190419_043838_add_column_table_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190419_043838_add_column_table_order cannot be reverted.\n";

        return false;
    }
    */
}
