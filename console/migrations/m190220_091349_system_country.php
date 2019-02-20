<?php

use yii\db\Migration;

/**
 * Class m190220_091349_system_country
 */
class m190220_091349_system_country extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('system_country',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'country_code' => $this->string(255)->comment(""),
            'country_code_2' => $this->string(255)->comment(""),
            'language' => $this->string(255)->comment("Nếu có nhiều , viết cách nhau bằng dấu phẩy"),
            'status' => $this->string(255)->comment(""),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190220_091349_system_country cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190220_091349_system_country cannot be reverted.\n";

        return false;
    }
    */
}
