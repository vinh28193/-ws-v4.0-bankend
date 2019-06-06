<?php

use yii\db\Migration;

class m190606_042826_create_table_WS_VISITOR_AGENT extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%VISITOR_AGENT}}', [
            'id' => $this->integer()->notNull(),
            'user_agent' => $this->text()->notNull(),
            'name' => $this->string(255),
            'info' => $this->text(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108846C00002$$', '{{%VISITOR_AGENT}}', '', true);
        $this->createIndex('SYS_IL0000108846C00004$$', '{{%VISITOR_AGENT}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%VISITOR_AGENT}}');
    }
}
