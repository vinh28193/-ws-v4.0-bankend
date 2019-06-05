<?php

use yii\db\Migration;

/**
 * Class m190521_073014_update_receiver_post_code_column_fron_order_table
 */
class m190521_073014_update_receiver_post_code_column_fron_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('order', 'receiver_post_code', $this->string(255)->null()->comment(" Mã bưu điện người nhận"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('order', 'receiver_post_code', $this->string(255)->notNull()->comment(" Mã bưu điện người nhận"));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190521_073014_update_receiver_post_code_column_fron_order_table cannot be reverted.\n";

        return false;
    }
    */
}
