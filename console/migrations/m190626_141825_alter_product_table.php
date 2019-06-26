<?php

use yii\db\Migration;

/**
 * Class m190626_141825_alter_product_table
 */
class m190626_141825_alter_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'total_final_amount_local', $this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'total_final_amount_local');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190626_141825_alter_product_table cannot be reverted.\n";

        return false;
    }
    */
}
