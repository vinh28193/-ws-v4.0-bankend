<?php

use yii\db\Migration;

/**
 * Class m190315_090823_update_columns_product
 */
class m190315_090823_update_columns_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','product_link','varchar(500)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190315_090823_update_columns_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190315_090823_update_columns_product cannot be reverted.\n";

        return false;
    }
    */
}
