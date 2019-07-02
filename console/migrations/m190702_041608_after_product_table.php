<?php

use yii\db\Migration;

/**
 * Class m190702_041608_after_product_table
 */
class m190702_041608_after_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('product','sku',$this->string(255)->null()->comment('sku của sản phẩm'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190702_041608_after_product_table reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190702_041608_after_product_table cannot be reverted.\n";

        return false;
    }
    */
}
