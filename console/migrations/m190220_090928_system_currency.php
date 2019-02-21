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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('system_currency',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'currency_code' => $this->string(255)->comment(""),
            'currency_symbol' => $this->string(255)->comment(""),
            'status' => $this->string(255)->comment(""),
        ],$tableOptions);
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
