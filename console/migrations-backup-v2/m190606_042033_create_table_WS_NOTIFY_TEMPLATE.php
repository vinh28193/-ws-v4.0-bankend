<?php

use yii\db\Migration;

class m190606_042033_create_table_WS_NOTIFY_TEMPLATE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%NOTIFY_TEMPLATE}}', [
            'id' => $this->integer()->notNull(),
            'type' => $this->string(255),
            'receive' => $this->string(255)->comment(' 0 : phone | 1: email'),
            'store' => $this->integer(),
            'from_name' => $this->string(255),
            'from_address' => $this->string(255),
            'to_name' => $this->string(255),
            'to_address' => $this->string(255),
            'subject' => $this->text(),
            'html_content' => $this->text(),
            'text_content' => $this->string(255),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108688C00010$$', '{{%NOTIFY_TEMPLATE}}', '', true);
        $this->createIndex('SYS_IL0000108688C00009$$', '{{%NOTIFY_TEMPLATE}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%NOTIFY_TEMPLATE}}');
    }
}
