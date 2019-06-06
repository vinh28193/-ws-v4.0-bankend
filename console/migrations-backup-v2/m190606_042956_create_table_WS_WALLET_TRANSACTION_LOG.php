<?php

use yii\db\Migration;

class m190606_042956_create_table_WS_WALLET_TRANSACTION_LOG extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%WALLET_TRANSACTION_LOG}}', [
            'id' => $this->integer()->notNull(),
            'wallet_transaction_id' => $this->integer(),
            'create_at' => $this->timestamp(),
            'update_at' => $this->timestamp(),
            'type' => $this->string(255),
            'user_name' => $this->string(255),
            'user_action' => $this->string(255),
            'content' => $this->string(1000),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%WALLET_TRANSACTION_LOG}}');
    }
}
