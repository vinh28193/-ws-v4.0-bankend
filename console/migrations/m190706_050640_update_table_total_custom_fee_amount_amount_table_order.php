<?php

use yii\db\Migration;

/**
 * Class m190706_050640_update_table_total_custom_fee_amount_amount_table_order
 */
class m190706_050640_update_table_total_custom_fee_amount_amount_table_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'total_custom_fee_amount_amount', $this->decimal()->comment('tiền phí hải quan amount'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order','total_custom_fee_amount_amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190706_050640_update_table_total_custom_fee_amount_amount_table_order cannot be reverted.\n";

        return false;
    }
    */
}
