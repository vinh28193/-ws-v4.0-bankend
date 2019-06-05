<?php

use yii\db\Migration;

/**
 * Class m190325_065916_update_column_table_product
 */
class m190325_065916_update_column_table_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','condition','varchar(255) NULL COMMENT \'Tình trạng đơn hàng\'');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190325_065916_update_column_table_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190325_065916_update_column_table_product cannot be reverted.\n";

        return false;
    }
    */
}
