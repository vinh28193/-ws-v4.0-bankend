<?php

use yii\db\Migration;

/**
 * Class m190702_101807_update_column_total_final_amount_origin_table_product
 */
class m190702_101807_update_column_total_final_amount_origin_table_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('product','total_final_amount_origin',$this->decimal());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('product','total_final_amount_origin');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190702_101807_update_column_total_final_amount_origin_table_product cannot be reverted.\n";

        return false;
    }
    */
}
