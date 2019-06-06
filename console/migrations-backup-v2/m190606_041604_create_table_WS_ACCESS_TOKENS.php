<?php

use yii\db\Migration;

class m190606_041604_create_table_WS_ACCESS_TOKENS extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%ACCESS_TOKENS}}', [
            'id' => $this->integer()->notNull(),
            'token' => $this->string(300)->notNull(),
            'expires_at' => $this->integer()->notNull(),
            'auth_code' => $this->string(200)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'app_id' => $this->string(200),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%ACCESS_TOKENS}}');
    }
}
