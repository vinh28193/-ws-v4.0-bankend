<?php

use yii\db\Migration;

/**
 * Class m190712_101729_add_column_order
 */
class m190712_101729_add_column_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','order_boxme',$this->string());
        $this->addColumn('order','shipment_boxme',$this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','order_boxme');
        $this->dropColumn('order','shipment_boxme');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190712_101729_add_column_order cannot be reverted.\n";

        return false;
    }
    */
}
