<?php

use yii\db\Migration;

class m190605_013400_create_table_access_tokens extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%access_tokens}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'token' => $this->string(300)->notNull(),
            'expires_at' => $this->integer(11)->notNull(),
            'auth_code' => $this->string(200)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'app_id' => $this->string(200),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%access_tokens}}');
    }
}
