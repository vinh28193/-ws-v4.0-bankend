<?php

use yii\db\Migration;

class m190605_013404_create_table_wallet_transaction_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%wallet_transaction_log}}', [
            'id' => $this->primaryKey(),
            'wallet_transaction_id' => $this->integer(11),
            'create_at' => $this->dateTime(),
            'update_at' => $this->dateTime(),
            'type' => $this->string(255),
            'user_name' => $this->string(255),
            'user_action' => $this->string(255),
            'content' => $this->string(1000),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%wallet_transaction_log}}');
    }
}
