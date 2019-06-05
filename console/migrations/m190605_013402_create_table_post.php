<?php

use yii\db\Migration;

class m190605_013402_create_table_post extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'text' => $this->text()->notNull(),
            'status' => $this->smallInteger(6)->notNull()->defaultValue('0'),
            'content_markdown' => $this->text(),
            'content_html' => $this->text(),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%post}}');
    }
}
