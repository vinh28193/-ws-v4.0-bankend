<?php

use yii\db\Migration;

/**
 * Class m190628_100202_add_column_tracking_and_product
 */
class m190628_100202_add_column_tracking_and_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','tracking_codes',$this->text()->comment('list tracking_code seller, Cách nhau dấu (,)'));
        $this->addColumn('tracking_code','product_ids',$this->string()->comment('list product id Cách nhau dấu (,)'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product','tracking_codes');
        $this->dropColumn('tracking_code','product_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190628_100202_add_column_tracking_and_product cannot be reverted.\n";

        return false;
    }
    */
}
