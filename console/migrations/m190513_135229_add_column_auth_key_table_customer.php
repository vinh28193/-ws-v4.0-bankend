<?php

use yii\db\Migration;

/**
 * Class m190513_135229_add_column_auth_key_table_customer
 */
class m190513_135229_add_column_auth_key_table_customer extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('customer', 'auth_key',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190513_135229_add_column_auth_key_table_customer cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190513_135229_add_column_auth_key_table_customer cannot be reverted.\n";

        return false;
    }
    */
}
