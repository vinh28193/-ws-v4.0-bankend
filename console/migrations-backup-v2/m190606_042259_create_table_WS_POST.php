<?php

use yii\db\Migration;

class m190606_042259_create_table_WS_POST extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%POST}}', [
            'id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'text' => $this->text()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue('0'),
            'content_markdown' => $this->text(),
            'content_html' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108756C00003$$', '{{%POST}}', '', true);
        $this->createIndex('SYS_IL0000108756C00005$$', '{{%POST}}', '', true);
        $this->createIndex('SYS_IL0000108756C00006$$', '{{%POST}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%POST}}');
    }
}
