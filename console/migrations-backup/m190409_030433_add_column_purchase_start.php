<?php

use yii\db\Migration;

/**
 * Class m190409_030433_add_column_purchase_start
 */
class m190409_030433_add_column_purchase_start extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','purchase_start','bigint(20) NULL AFTER `new`');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190409_030433_add_column_purchase_start cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190409_030433_add_column_purchase_start cannot be reverted.\n";

        return false;
    }
    */
}
