<?php

use yii\db\Migration;

/**
 * Class m190531_031428_add_column_locale_to_user_and_store
 */
class m190531_031428_add_column_locale_to_user_and_store extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'locale', $this->string(10)->after('store_id'));
        $this->update('user', [
            'locale' => 'vi'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'locale');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190531_031428_add_column_locale_to_user_and_store cannot be reverted.\n";

        return false;
    }
    */
}
