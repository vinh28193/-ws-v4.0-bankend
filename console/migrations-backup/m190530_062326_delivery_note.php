<?php

use yii\db\Migration;

/**
 * Class m190530_062326_delivery_note
 */
class m190530_062326_delivery_note extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('delivery_note','receiver_address_id',$this->integer()->comment('id địa chỉ nhận của khách')->after('customer_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190530_062326_delivery_note cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190530_062326_delivery_note cannot be reverted.\n";

        return false;
    }
    */
}
