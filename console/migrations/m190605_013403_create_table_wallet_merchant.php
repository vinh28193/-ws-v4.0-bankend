<?php

use yii\db\Migration;

class m190605_013403_create_table_wallet_merchant extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%wallet_merchant}}', [
            'id' => $this->primaryKey(),
            'account_number' => $this->string(50)->notNull()->defaultValue('')->comment('Mã ví - Ma tien to: S(danh cho master)  , nội bộ '),
            'account_email' => $this->string(255)->comment('Email '),
            'account_token' => $this->string(255),
            'account_bank_id' => $this->string(255),
            'parent_account_id' => $this->integer(11),
            'description' => $this->string(255)->comment('mo ta ve tai khoan'),
            'opening_balance' => $this->decimal(18, 2)->comment('Số dư mở ví'),
            'current_balance' => $this->decimal(18, 2)->comment('Số dư hiện tại'),
            'total_credit_amount' => $this->decimal(18, 2)->comment('Tổng số giao dịch phát sinh tăng'),
            'total_debit_amount' => $this->decimal(18, 2)->comment('Tổng số giao dịch phát sinh giảm'),
            'previous_current_balance' => $this->decimal(18, 2)->comment('Số dư kỳ trước '),
            'last_amount' => $this->decimal(18, 2),
            'last_updated' => $this->dateTime(),
            'last_edit_user_id' => $this->integer(11),
            'note' => $this->string(500),
            'store_id' => $this->integer(11),
            'active' => $this->tinyInteger(1),
            'account_ref_payment_mapping' => $this->string(255)->comment('Mã tài khoản ngân lượng / IdoMog mapping tùy thuộc vào StoreId'),
            'payment_provider_id' => $this->integer(11),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%wallet_merchant}}');
    }
}
