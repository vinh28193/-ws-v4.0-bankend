<?php

use yii\db\Migration;

/**
 * Class m190703_100830_alter_order_table
 */
class m190703_100830_alter_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190703_100830_alter_order_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190703_100830_alter_order_table cannot be reverted.\n";

        return false;
    }
    */
}
