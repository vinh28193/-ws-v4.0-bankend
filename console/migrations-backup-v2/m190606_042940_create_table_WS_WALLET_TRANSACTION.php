<?php

use yii\db\Migration;

class m190606_042940_create_table_WS_WALLET_TRANSACTION extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%WALLET_TRANSACTION}}', [
            'id' => $this->integer()->notNull(),
            'wallet_transaction_code' => $this->string(255)->notNull(),
            'wallet_client_id' => $this->integer()->notNull(),
            'wallet_merchant_id' => $this->integer()->notNull(),
            'type' => $this->string(255)->comment('TOP_UP/FREEZE/UN_FREEZE/PAY_ORDER/REFUND/WITH_DRAW'),
            'order_number' => $this->string(255)->comment('M? thanh toan (order, addfee)'),
            'status' => $this->integer()->comment('0:Queue//1:processing//2:complete//3:cancel//4:fail'),
            'amount' => $this->decimal(),
            'credit_amount' => $this->decimal()->comment('S? ti?n n?p vao tai kho?n khach(Topup,refund...)'),
            'debit_amount' => $this->decimal()->comment('S? ti?n khach thanh toan'),
            'note' => $this->string(255),
            'description' => $this->string(255)->comment('Mo t? giao d?ch'),
            'verify_receive_type' => $this->integer()->comment('Kieu xac thuc 0:phone,1:email'),
            'verify_code' => $this->string(10)->comment('OTP code'),
            'verify_count' => $this->integer(),
            'verify_expired_at' => $this->integer(),
            'verified_at' => $this->timestamp()->comment('Thoi gian xac thuc otp'),
            'refresh_count' => $this->integer(),
            'refresh_expired_at' => $this->string(255),
            'create_at' => $this->timestamp()->notNull(),
            'update_at' => $this->timestamp(),
            'complete_at' => $this->timestamp()->comment('Thoi gian giao dich thanh cong'),
            'cancel_at' => $this->timestamp()->comment('Thoi gian giao dich bi huy'),
            'fail_at' => $this->timestamp()->comment('Thoi gian giao dich that bai'),
            'payment_method' => $this->string(255),
            'payment_provider_name' => $this->string(255),
            'payment_bank_code' => $this->string(255),
            'payment_transaction' => $this->string(255),
            'request_content' => $this->string(1000),
            'response_content' => $this->string(1000),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%WALLET_TRANSACTION}}');
    }
}
