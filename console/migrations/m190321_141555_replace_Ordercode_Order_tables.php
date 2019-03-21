<?php

use yii\db\Migration;

/**
 * Class m190321_141555_replace_Ordercode_Order_tables
 */
class m190321_141555_replace_Ordercode_Order_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('order','ordercode','varchar(255)');
        $this->addColumn('order','ordercode',$this->string(255)->after('id')->comment('ordercode : BIN Code Weshop : WSVN , WSINDO'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190321_141555_replace_Ordercode_Order_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190321_141555_replace_Ordercode_Order_tables cannot be reverted.\n";

        return false;
    }
    */
}
