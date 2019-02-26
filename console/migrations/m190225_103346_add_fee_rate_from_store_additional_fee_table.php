<?php

use yii\db\Migration;

/**
 * Class m190225_103346_add_fee_rate_from_store_additional_fee_table
 */
class m190225_103346_add_fee_rate_from_store_additional_fee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('store_additional_fee','fee_rate', $this->decimal(18,2)->defaultValue(0.00)->comment('Fee Rate'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('store_additional_fee','fee_rate');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190225_103346_add_fee_rate_from_store_additional_fee_table cannot be reverted.\n";

        return false;
    }
    */
}
