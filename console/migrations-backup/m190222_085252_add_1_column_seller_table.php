<?php

use yii\db\Migration;

/**
 * Class m190222_085252_add_1_column_seller_table
 */
class m190222_085252_add_1_column_seller_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('seller','portal','text');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190222_085252_add_1_column_seller_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190222_085252_add_1_column_seller_table cannot be reverted.\n";

        return false;
    }
    */
}
