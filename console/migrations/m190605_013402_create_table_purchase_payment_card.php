<?php

use yii\db\Migration;

class m190605_013402_create_table_purchase_payment_card extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%purchase_payment_card}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'card_code' => $this->string(255),
            'balance' => $this->decimal(10),
            'current_balance' => $this->decimal(10),
            'last_transaction_time' => $this->integer(11),
            'last_amount' => $this->integer(11),
            'store_id' => $this->integer(11),
            'status' => $this->integer(11)->defaultValue('1'),
            'updated_at' => $this->integer(11),
            'created_at' => $this->integer(11),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%purchase_payment_card}}');
    }
}
