<?php

use yii\db\Migration;

/**
 * Class m190524_144058_update_purchase
 */
class m190524_144058_update_purchase extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('purchase_order','transaction_payment',$this->string()->comment('Mã giao dịch thanh toán paypal.'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190524_144058_update_purchase cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190524_144058_update_purchase cannot be reverted.\n";

        return false;
    }
    */
}
