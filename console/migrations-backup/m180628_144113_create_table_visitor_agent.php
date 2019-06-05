<?php

use yii\db\Migration;

class m180628_144113_create_table_visitor_agent extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%visitor_agent}}', [
            'id' => $this->primaryKey(),
            'user_agent' => $this->text()->notNull(),
            'name' => $this->string(),
            'info' => $this->text(),
                ], $tableOptions);


    }

    public function down() {
        $this->dropTable('{{%visitor_agent}}');
    }

}
