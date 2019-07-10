<?php

use yii\db\Migration;

/**
 * Class m190710_022135_add_colunm_order
 */
class m190710_022135_add_colunm_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order','purchase_note',$this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','purchase_note');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190710_022135_add_colunm_order cannot be reverted.\n";

        return false;
    }
    */
}
