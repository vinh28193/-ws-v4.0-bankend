<?php

use yii\db\Migration;

/**
 * Class m190315_123532_change_type_colum_In_Order_table
 */
class m190315_123532_change_type_colum_In_Order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('order','total_weight_temporary','decimal(18,2)');
      $this->alterColumn('order','total_weight','decimal(18,2)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190315_123532_change_type_colum_In_Order_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190315_123532_change_type_colum_In_Order_table cannot be reverted.\n";

        return false;
    }
    */
}
