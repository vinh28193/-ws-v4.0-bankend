<?php

use yii\db\Migration;

class m190605_013403_create_table_wallet_transaction extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%wallet_transaction}}', [
            'id' => $this->primaryKey(),
            'wallet_transaction_code' => $this->string(255)->notNull(),
            'wallet_client_id' => $this->integer(11)->notNull(),
            'wallet_merchant_id' => $this->integer(11)->notNull(),
            'type' => $this->string(255)->comment('TOP_UP/FREEZE/UN_FREEZE/PAY_ORDER/REFUND/WITH_DRAW'),
            'order_number' => $this->string(255)->comment('Mã thanh toán (order, addfee)'),
            'status' => $this->integer(11)->comment('0:Queue//1:processing//2:complete//3:cancel//4:fail'),
            'amount' => $this->double(),
            'credit_amount' => $this->double()->comment('Số tiền nạp vào tài khoản khách(Topup,refund...)'),
            'debit_amount' => $this->double()->comment('Số tiền khách thanh toán'),
            'note' => $this->string(255),
            'description' => $this->string(255)->comment('Mô tả giao dịch'),
            'verify_receive_type' => $this->integer(11)->comment('Kieu xac thuc 0:phone,1:email'),
            'verify_code' => $this->string(10)->comment('OTP code'),
            'verify_count' => $this->integer(11),
            'verify_expired_at' => $this->integer(11),
            'verified_at' => $this->dateTime()->comment('Thoi gian xac thuc otp'),
            'refresh_count' => $this->integer(11),
            'refresh_expired_at' => $this->string(255),
            'create_at' => $this->dateTime()->notNull(),
            'update_at' => $this->dateTime(),
            'complete_at' => $this->dateTime()->comment('Thoi gian giao dich thanh cong'),
            'cancel_at' => $this->dateTime()->comment('Thoi gian giao dich bi huy'),
            'fail_at' => $this->dateTime()->comment('Thoi gian giao dich that bai'),
            'payment_method' => $this->string(255),
            'payment_provider_name' => $this->string(255),
            'payment_bank_code' => $this->string(255),
            'payment_transaction' => $this->string(255),
            'request_content' => $this->string(1000),
            'response_content' => $this->string(1000),
        ], $tableOptions);

        $this->createIndex('wallet_merchant_id', '{{%wallet_transaction}}', 'wallet_merchant_id');
        $this->createIndex('wallet_client_id', '{{%wallet_transaction}}', 'wallet_client_id');
        $this->addForeignKey('wallet_transaction_ibfk_1', '{{%wallet_transaction}}', 'wallet_client_id', '{{%wallet_client}}', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('wallet_transaction_ibfk_2', '{{%wallet_transaction}}', 'wallet_merchant_id', '{{%wallet_merchant}}', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('{{%wallet_transaction}}');
    }
}
