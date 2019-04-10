<?php

use yii\db\Migration;

/**
 * Class m190409_043950_add_column_product
 */
class m190409_043950_add_column_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','price_purchase','decimal(18, 2) NULL COMMENT \'Giá khi nhân viên mua hàng\' AFTER `quantity_inspect`');
        $this->addColumn('product','shipping_fee_purchase','decimal(18, 2) NULL COMMENT \'Phí ship khi nhân viên mua hàng\' AFTER `price_purchase`');
        $this->addColumn('product','tax_fee_purchase','decimal(18, 2) NULL COMMENT \'Phí tax khi nhân viên mua hàng\' AFTER `shipping_fee_purchase`');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190409_043950_add_column_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190409_043950_add_column_product cannot be reverted.\n";

        return false;
    }
    */
}
