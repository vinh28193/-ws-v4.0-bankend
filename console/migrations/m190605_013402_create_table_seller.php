<?php

use yii\db\Migration;

class m190605_013402_create_table_seller extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%seller}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('ID'),
            'seller_name' => $this->string(255),
            'seller_link_store' => $this->text(),
            'seller_store_rate' => $this->string(255),
            'seller_store_description' => $this->text(),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'seller_remove' => $this->tinyInteger(4),
            'portal' => $this->text(),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%seller}}');
    }
}
