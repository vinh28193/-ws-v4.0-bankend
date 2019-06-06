<?php

use yii\db\Migration;

class m190606_042450_create_table_WS_PURCHASE_PAYMENT_CARD extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PURCHASE_PAYMENT_CARD}}', [
            'id' => $this->integer()->notNull(),
            'card_code' => $this->string(255),
            'balance' => $this->decimal(),
            'current_balance' => $this->decimal(),
            'last_transaction_time' => $this->integer(),
            'last_amount' => $this->integer(),
            'store_id' => $this->integer(),
            'status' => $this->integer()->defaultValue('1'),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%PURCHASE_PAYMENT_CARD}}');
    }
}
