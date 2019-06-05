<?php

use yii\db\Migration;

/**
 * Class m190322_062942_add_version_field_wallet_transation_tables
 */
class m190322_062942_add_version_field_wallet_transation_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('wallet_transaction', 'version',$this->string(255)->defaultValue('4.0')->comment('version 4.0'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190322_062942_add_version_field_wallet_transation_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190322_062942_add_version_field_wallet_transation_tables cannot be reverted.\n";

        return false;
    }
    */
}
