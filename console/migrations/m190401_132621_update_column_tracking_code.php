<?php

use yii\db\Migration;

/**
 * Class m190401_132621_update_column_tracking_code
 */
class m190401_132621_update_column_tracking_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tracking_code','order_ids','varchar(255) NULL COMMENT \'Order id(s)\'');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190401_132621_update_column_tracking_code cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190401_132621_update_column_tracking_code cannot be reverted.\n";

        return false;
    }
    */
}
