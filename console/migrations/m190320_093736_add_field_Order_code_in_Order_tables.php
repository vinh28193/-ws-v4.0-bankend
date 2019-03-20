<?php

use yii\db\Migration;

/**
 * Class m190320_093736_add_field_Order_code_in_Order_tables
 */
class m190320_093736_add_field_Order_code_in_Order_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','Ordercode','varchar(255)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->dropColumn('order','Ordercode');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190320_093736_add_field_Order_code_in_Order_tables cannot be reverted.\n";

        return false;
    }
    */
}
