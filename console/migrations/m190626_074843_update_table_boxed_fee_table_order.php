<?php

use yii\db\Migration;

/**
 * Class m190626_074843_update_table_boxed_fee_table_order
 */
class m190626_074843_update_table_boxed_fee_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'boxed_fee', $this->integer(50)->comment("phí đóng gỗ, đóng hộp"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'boxed_fee');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190626_074843_update_table_boxed_fee_table_order cannot be reverted.\n";

        return false;
    }
    */
}
