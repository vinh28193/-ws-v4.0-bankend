<?php

use yii\db\Migration;

/**
 * Class m190226_092328_insert_test_fee_into_store_additional_fee_table
 */
class m190226_092328_insert_test_fee_into_store_additional_fee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
//    public function safeUp()
//    {
//        $this->insert('store_additional_fee', [
//            'name' => 'test_fee',
//            'currency' => 'VND',
//            'description' => 'dev fee',
//            'is_convert' => 1,
//            'is_read_only' => 0
//        ]);
//    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190226_092328_insert_test_fee_into_store_additional_fee_table cannot be reverted.\n";
        $this->delete('store_additional_fee', ['name' => 'test_fee']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190226_092328_insert_test_fee_into_store_additional_fee_table cannot be reverted.\n";

        return false;
    }
    */
}
