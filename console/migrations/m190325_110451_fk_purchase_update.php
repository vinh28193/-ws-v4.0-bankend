<?php

use yii\db\Migration;

/**
 * Class m190325_110451_fk_purchase_update
 */
class m190325_110451_fk_purchase_update extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-purchase-product-product','purchase_product','product_id','product','id');
        $this->addForeignKey('fk-purchase-product-purchase_order','purchase_product','purchase_order_id','purchase_order','id');
        $this->addForeignKey('fk-purchase-product-order','purchase_product','order_id','order','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190325_110451_fk_purchase_update cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190325_110451_fk_purchase_update cannot be reverted.\n";

        return false;
    }
    */
}
