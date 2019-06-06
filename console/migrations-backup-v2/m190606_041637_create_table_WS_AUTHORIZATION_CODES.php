<?php

use yii\db\Migration;

class m190606_041637_create_table_WS_AUTHORIZATION_CODES extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%AUTHORIZATION_CODES}}', [
            'id' => $this->integer()->notNull(),
            'code' => $this->string(150)->notNull(),
            'expires_at' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->string(50)->defaultValue('user'),
            'app_id' => $this->string(200),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%AUTHORIZATION_CODES}}');
    }
}
