<?php

use yii\db\Migration;

/**
 * Class m190329_025107_Total_fee_in_product_Table_proudct
 */
class m190329_025107_Total_fee_in_product_Table_proudct extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','total_fee_product_local',$this->decimal(18, 2)->after('total_price_amount_local')->comment("tổng phí trên sản phẩm"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190329_025107_Total_fee_in_product_Table_proudct cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190329_025107_Total_fee_in_product_Table_proudct cannot be reverted.\n";

        return false;
    }
    */
}
