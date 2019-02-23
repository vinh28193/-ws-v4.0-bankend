<?php

use yii\db\Migration;

/**
 * Class m190223_025404_insert_into_store_table
 */
class m190223_025404_insert_into_store_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('store', ['id', 'locale', 'name', 'country_name', 'address', 'url', 'currency', 'status', 'env'], [
            [1, 'vi', 'Weshop Dev VN', ' Viet Nam', '18 Tam Trinh', 'weshop-4.0.frontend.vn', 'VND', 1, 'dev'],
            [2, 'id', 'Weshop Dev ID', ' ID', '18 Tam Trinh', 'weshop-4.0.frontend.id', 'IDR', 1, 'dev'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190223_025404_insert_into_store_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_025404_insert_into_store_table cannot be reverted.\n";

        return false;
    }
    */
}
