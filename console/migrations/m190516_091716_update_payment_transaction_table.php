<?php

use yii\db\Migration;

/**
 * Class m190516_091716_update_payment_transaction_table
 */
class m190516_091716_update_payment_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('payment_transaction','coupon_code',$this->string(32)->null());
        $this->alterColumn('payment_transaction','payment_bank_code',$this->string(32)->null());
        $this->addColumn('payment_transaction','payment_type',$this->string(10)->notNull()->after('transaction_customer_country'));
        $this->dropColumn('payment_transaction','orders');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('payment_transaction','coupon_code',$this->string(32)->notNull());
        $this->alterColumn('payment_transaction','payment_bank_code',$this->string(32)->notNull());
        $this->addColumn('payment_transaction','orders',$this->text()->notNull()->comment('list order')->after('bulk_point'));
        $this->dropColumn('payment_transaction','payment_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190516_091716_update_payment_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
