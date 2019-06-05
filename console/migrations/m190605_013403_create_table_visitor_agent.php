<?php

use yii\db\Migration;

class m190605_013403_create_table_visitor_agent extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%visitor_agent}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'user_agent' => $this->text()->notNull(),
            'name' => $this->string(255),
            'info' => $this->text(),
        ], $tableOptions);

        $this->createIndex('va_ua_vl_fkey', '{{%visitor_agent}}', 'id');
    }

    public function down()
    {
        $this->dropTable('{{%visitor_agent}}');
    }
}
