<?php

use yii\db\Migration;

class m190606_042018_create_table_WS_MESSAGE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%MESSAGE}}', [
            'id' => $this->integer()->notNull(),
            'language' => $this->string(16)->notNull(),
            'translation' => $this->text(),
        ], $tableOptions);

        $this->addPrimaryKey('pk_message_id_language', '{{%MESSAGE}}', ['ID', 'LANGUAGE']);
        $this->createIndex('SYS_IL0000108880C00003$$', '{{%MESSAGE}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%MESSAGE}}');
    }
}
