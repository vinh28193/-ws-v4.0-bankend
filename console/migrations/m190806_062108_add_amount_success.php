<?php

use yii\db\Migration;

/**
 * Class m190806_062108_add_amount_success
 */
class m190806_062108_add_amount_success extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_transaction', 'total_amount_success', $this->decimal()->comment('số tiền đã thực hiện thanh toán, refund thành công'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment_transaction','total_amount_success');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190806_062108_add_amount_success cannot be reverted.\n";

        return false;
    }
    */
}
