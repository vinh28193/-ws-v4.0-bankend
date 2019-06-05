<?php

use yii\db\Migration;

class m180628_144042_create_table_visitor_log extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%visitor_log}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('now()'),
            'request' => $this->string()->notNull(),
            'referer' => $this->text(),
            'user_agent' => $this->text(),
                ], $tableOptions);

        }

    public function down() {
        $this->dropTable('{{%visitor_log}}');
    }

}
