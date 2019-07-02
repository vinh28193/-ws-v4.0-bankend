<?php

use yii\db\Migration;

/**
 * Class m190702_100908_addcolumn_into_payment_transaction
 */
class m190702_100908_addcolumn_into_payment_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_transaction', 'courier_delivery_time', $this->string(255)->null()->after('service_code'));
        $this->addColumn('payment_transaction', 'support_id', $this->integer(11)->null()->after('shipping'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_transaction', 'courier_delivery_time');
        $this->dropColumn('payment_transaction', 'support_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190702_100908_addcolumn_into_payment_transaction cannot be reverted.\n";

        return false;
    }
    */
}
