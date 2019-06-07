<?php

use yii\db\Migration;

/**
 * Class m190222_090850_add_3_column_product_table
 */
class m190222_090850_add_3_column_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','currency_id','int');
        $this->addColumn('product','currency_symbol','varchar(255)');
        $this->addColumn('product','exchange_rate','decimal');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190222_090850_add_3_column_product_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190222_090850_add_3_column_product_table cannot be reverted.\n";

        return false;
    }
    */
}
