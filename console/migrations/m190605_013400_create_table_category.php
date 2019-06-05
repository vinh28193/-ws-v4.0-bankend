<?php

use yii\db\Migration;

class m190605_013400_create_table_category extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('ID'),
            'alias' => $this->string(255),
            'site' => $this->string(255)->comment('ebay / amazon / amazon-jp'),
            'origin_name' => $this->string(255),
            'category_group_id' => $this->integer(11),
            'parent_id' => $this->string(255),
            'description' => $this->string(255),
            'weight' => $this->double(),
            'inter_shipping_b' => $this->double(),
            'custom_fee' => $this->decimal(18, 2),
            'level' => $this->integer(11),
            'path' => $this->string(255),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'active' => $this->tinyInteger(4),
            'remove' => $this->tinyInteger(4),
            'name' => $this->string(500),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%category}}');
    }
}
