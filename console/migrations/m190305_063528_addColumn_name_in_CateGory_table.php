<?php

use yii\db\Migration;

/**
 * Class m190305_063528_addColumn_name_in_CateGory_table
 */
class m190305_063528_addColumn_name_in_CateGory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("category",'name','varchar(500)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190305_063528_addColumn_name_in_CateGory_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190305_063528_addColumn_name_in_CateGory_table cannot be reverted.\n";

        return false;
    }
    */
}
