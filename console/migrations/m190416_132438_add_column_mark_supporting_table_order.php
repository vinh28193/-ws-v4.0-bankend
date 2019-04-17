<?php

use yii\db\Migration;

/**
 * Class m190416_132438_add_column_mark_supporting_table_order
 */
class m190416_132438_add_column_mark_supporting_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','mark_supporting','bigint(20) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190416_132438_add_column_mark_supporting_table_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190416_132438_add_column_mark_supporting_table_order cannot be reverted.\n";

        return false;
    }
    */
}
