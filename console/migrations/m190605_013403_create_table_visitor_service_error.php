<?php

use yii\db\Migration;

class m190605_013403_create_table_visitor_service_error extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%visitor_service_error}}', [
            'id' => $this->primaryKey(),
            'service' => $this->string(255)->notNull(),
            'url' => $this->string(255)->notNull(),
            'params' => $this->text(),
            'message' => $this->text()->notNull(),
            'is_resolved' => $this->tinyInteger(1)->notNull()->defaultValue('0'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%visitor_service_error}}');
    }
}
