<?php

use yii\db\Migration;

/**
 * Class m190621_023649_update_column_additional_service_table_order
 */
class m190621_023649_update_column_additional_service_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'additional_service' , $this->integer(50)->comment("dịch vụ cộng thêm"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190621_023649_update_column_additional_service_table_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190621_023649_update_column_additional_service_table_order cannot be reverted.\n";

        return false;
    }
    */
}
