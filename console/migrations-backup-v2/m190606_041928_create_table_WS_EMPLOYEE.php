<?php

use yii\db\Migration;

class m190606_041928_create_table_WS_EMPLOYEE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%EMPLOYEE}}', [
            'id' => $this->integer()->notNull(),
            'name' => $this->string(200)->notNull(),
            'email' => $this->string(100)->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%EMPLOYEE}}');
    }
}
