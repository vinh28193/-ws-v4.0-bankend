<?php

use yii\db\Migration;

/**
 * Class m190703_070250_update_total_price_amount_origin_table_product
 */
class m190703_070250_update_total_price_amount_origin_table_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','total_price_amount_origin',$this->decimal());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','total_price_amount_origin');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190703_070250_update_total_price_amount_origin_table_product cannot be reverted.\n";

        return false;
    }
    */
}
