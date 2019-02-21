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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('system_country',[
            'id' => $this->primaryKey()->comment("ID"),
            'name' => $this->string(255)->comment(""),
            'country_code' => $this->string(255)->comment(""),
            'country_code_2' => $this->string(255)->comment(""),
            'language' => $this->string(255)->comment("Nếu có nhiều , viết cách nhau bằng dấu phẩy"),
            'status' => $this->string(255)->comment(""),
        ],$tableOptions);
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
