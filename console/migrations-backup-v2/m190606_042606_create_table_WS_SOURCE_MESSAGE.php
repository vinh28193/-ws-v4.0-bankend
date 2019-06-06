<?php

use yii\db\Migration;

class m190606_042606_create_table_WS_SOURCE_MESSAGE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SOURCE_MESSAGE}}', [
            'id' => $this->integer()->notNull(),
            'category' => $this->string(255),
            'message' => $this->text(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108876C00003$$', '{{%SOURCE_MESSAGE}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%SOURCE_MESSAGE}}');
    }
}
