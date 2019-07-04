<?php

use yii\db\Migration;

/**
 * Class m190704_095904_update_column_price_policy_table_product
 */
class m190704_095904_update_column_price_policy_table_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product', 'price_policy', $this->decimal()->comment('tiền phí hải quan'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'price_policy');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190704_095904_update_column_price_policy_table_product cannot be reverted.\n";

        return false;
    }
    */
}
