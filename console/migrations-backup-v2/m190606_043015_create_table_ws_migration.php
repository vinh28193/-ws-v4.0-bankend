<?php

use yii\db\Migration;

class m190606_043015_create_table_ws_migration extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%ws_migration}}', [
            'version' => $this->string(180)->notNull()->append('PRIMARY KEY'),
            'apply_time' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%ws_migration}}');
    }
}
