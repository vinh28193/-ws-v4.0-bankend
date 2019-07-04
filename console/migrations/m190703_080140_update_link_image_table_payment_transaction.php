<?php

use yii\db\Migration;

/**
 * Class m190703_080140_update_link_image_table_payment_transaction
 */
class m190703_080140_update_link_image_table_payment_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_transaction','link_image',$this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_transaction','link_image');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190703_080140_update_link_image_table_payment_transaction cannot be reverted.\n";

        return false;
    }
    */
}
