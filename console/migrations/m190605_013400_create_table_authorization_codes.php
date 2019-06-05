<?php

use yii\db\Migration;

class m190605_013400_create_table_authorization_codes extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%authorization_codes}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'code' => $this->string(150)->notNull(),
            'expires_at' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'type' => $this->string(50)->defaultValue('user'),
            'app_id' => $this->string(200),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%authorization_codes}}');
    }
}
