<?php

use yii\db\Migration;

/**
 * Class m190220_090928_system_currency
 */
class m190220_090928_system_currency extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('system_currency',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'currency_code' => $this->string(255)->comment(""),
            'currency_symbol' => $this->string(255)->comment(""),
            'status' => $this->string(255)->comment(""),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190220_090928_system_currency cannot be reverted.\n";

        return false;
    }
    */
}
