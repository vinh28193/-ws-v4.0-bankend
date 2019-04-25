<?php

use yii\db\Migration;

/**
 * Class m190425_015944_add_column_Item_name
 */
class m190425_015944_add_column_Item_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('draft_data_tracking','item_name',$this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190425_015944_add_column_Item_name cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190425_015944_add_column_Item_name cannot be reverted.\n";

        return false;
    }
    */
}
