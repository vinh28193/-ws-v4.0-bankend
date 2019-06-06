<?php

use yii\db\Migration;

class m190606_041648_create_table_WS_CATEGORY extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%CATEGORY}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'alias' => $this->string(255),
            'site' => $this->string(255)->comment('ebay / amazon / amazon-jp'),
            'origin_name' => $this->string(255),
            'category_group_id' => $this->integer(),
            'parent_id' => $this->string(255),
            'description' => $this->string(255),
            'weight' => $this->decimal(),
            'inter_shipping_b' => $this->decimal(),
            'custom_fee' => $this->decimal(),
            'level' => $this->integer(),
            'path' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'active' => $this->integer(),
            'remove' => $this->integer(),
            'name' => $this->string(500),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%CATEGORY}}');
    }
}
