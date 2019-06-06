<?php

use yii\db\Migration;

class m190606_042026_create_table_WS_MIGRATION extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%MIGRATION}}', [
            'version' => $this->string(180)->notNull(),
            'apply_time' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%MIGRATION}}');
    }
}
