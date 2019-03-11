<?php

use yii\db\Migration;

/**
 * Class m190307_061659_add_product_id_column_into_order_fee_table
 */
class m190307_061659_add_product_id_column_into_order_fee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order_fee', 'product_id', $this->integer(11)->notNull()->comment('Product Id')->after('order_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190307_061659_add_product_id_column_into_order_fee_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190307_061659_add_product_id_column_into_order_fee_table cannot be reverted.\n";

        return false;
    }
    */
}
