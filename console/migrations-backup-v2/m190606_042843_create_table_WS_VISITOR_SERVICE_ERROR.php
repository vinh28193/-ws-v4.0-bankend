<?php

use yii\db\Migration;

class m190606_042843_create_table_WS_VISITOR_SERVICE_ERROR extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%VISITOR_SERVICE_ERROR}}', [
            'id' => $this->integer()->notNull(),
            'service' => $this->string(255)->notNull(),
            'url' => $this->string(255)->notNull(),
            'params' => $this->text(),
            'message' => $this->text()->notNull(),
            'is_resolved' => $this->integer()->notNull()->defaultValue('0'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108858C00005$$', '{{%VISITOR_SERVICE_ERROR}}', '', true);
        $this->createIndex('SYS_IL0000108858C00004$$', '{{%VISITOR_SERVICE_ERROR}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%VISITOR_SERVICE_ERROR}}');
    }
}
