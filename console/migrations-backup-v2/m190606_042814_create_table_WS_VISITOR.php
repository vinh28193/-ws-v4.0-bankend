<?php

use yii\db\Migration;

class m190606_042814_create_table_WS_VISITOR extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%VISITOR}}', [
            'ip' => $this->string(50)->notNull(),
            'is_blacklisted' => $this->integer()->notNull()->defaultValue('0'),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
            'user_id' => $this->integer(),
            'name' => $this->string(255),
            'message' => $this->text(),
            'visits' => $this->integer()->notNull()->defaultValue('0'),
            'city' => $this->string(255),
            'region' => $this->string(255),
            'country' => $this->string(255),
            'latitude' => $this->decimal(),
            'longitude' => $this->decimal(),
            'organization' => $this->string(255),
            'proxy' => $this->string(255),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108842C00007$$', '{{%VISITOR}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%VISITOR}}');
    }
}
