<?php

use yii\db\Migration;

/**
 * Class m190525_072532_update_product
 */
class m190525_072532_update_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','current_status',$this->string()->defaultValue('NEW'));
        $this->addColumn('product','purchase_start',$this->integer());
        $this->addColumn('product','purchased',$this->integer());
        $this->addColumn('product','seller_shipped',$this->integer());
        $this->addColumn('product','stockin_us',$this->integer());
        $this->addColumn('product','stockout_us',$this->integer());
        $this->addColumn('product','stockin_local',$this->integer());
        $this->addColumn('product','stockout_local',$this->integer());
        $this->addColumn('product','at_customer',$this->integer());
        $this->addColumn('product','returned',$this->integer());
        $this->addColumn('product','cancel',$this->integer());
        $this->addColumn('product','lost',$this->integer());
        $this->addColumn('product','refunded',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190525_072532_update_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190525_072532_update_product cannot be reverted.\n";

        return false;
    }
    */
}
