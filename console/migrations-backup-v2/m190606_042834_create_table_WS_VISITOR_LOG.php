<?php

use yii\db\Migration;

class m190606_042834_create_table_WS_VISITOR_LOG extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%VISITOR_LOG}}', [
            'id' => $this->integer()->notNull(),
            'ip' => $this->string(50)->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'request' => $this->string(255)->notNull(),
            'referer' => $this->text(),
            'user_agent' => $this->text(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108852C00005$$', '{{%VISITOR_LOG}}', '', true);
        $this->createIndex('SYS_IL0000108852C00006$$', '{{%VISITOR_LOG}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%VISITOR_LOG}}');
    }
}
