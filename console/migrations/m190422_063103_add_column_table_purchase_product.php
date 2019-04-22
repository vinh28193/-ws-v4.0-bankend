<?php

use yii\db\Migration;

/**
 * Class m190422_063103_add_column_table_purchase_product
 */
class m190422_063103_add_column_table_purchase_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('purchase_product','seller_refund_amount',"decimal(18,2) COMMENT 'Số tiền người bán hoàn chả'");
        $this->addColumn('product','seller_refund_amount',"decimal(18,2) COMMENT 'Số tiền người bán hoàn chả'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190422_063103_add_column_table_purchase_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190422_063103_add_column_table_purchase_product cannot be reverted.\n";

        return false;
    }
    */
}
