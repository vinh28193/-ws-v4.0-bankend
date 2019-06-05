<?php

use yii\db\Migration;

class m190605_013403_create_table_visitor_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%visitor_log}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string(50)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'request' => $this->string(255)->notNull(),
            'referer' => $this->text(),
            'user_agent' => $this->text(),
        ], $tableOptions);

        $this->createIndex('fki_vl_va_ua_fkey', '{{%visitor_log}}', 'id');
        $this->createIndex('visits_ip_idx', '{{%visitor_log}}', 'ip');
        $this->createIndex('visits_timestamp_idx', '{{%visitor_log}}', 'created_at');
       // $this->addForeignKey('visits_visitor_fkey', '{{%visitor_log}}', 'ip', '{{%visitor}}', 'ip', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%visitor_log}}');
    }
}
