<?php

use yii\db\Migration;

/**
 * Class m190327_125742_add_column_in_table_purchase_order
 */
class m190327_125742_add_column_in_table_purchase_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('purchase_order','updated_by','int(11) not null');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190327_125742_add_column_in_table_purchase_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190327_125742_add_column_in_table_purchase_order cannot be reverted.\n";

        return false;
    }
    */
}
