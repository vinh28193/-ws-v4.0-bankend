<?php

use yii\db\Migration;

class m190606_042853_create_table_WS_WALLET_CLIENT extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%WALLET_CLIENT}}', [
            'id' => $this->integer()->notNull(),
            'username' => $this->string(255)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255),
            'auth_key' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'customer_phone' => $this->string(255)->notNull(),
            'customer_name' => $this->string(255),
            'current_balance' => $this->decimal()->notNull()->defaultValue('\'0\'')->comment('T?ng s? d? hi?n t?i (=freeze_balance+usable_balance)'),
            'freeze_balance' => $this->decimal()->notNull()->defaultValue('\'0\'')->comment('S? ti?n b? dong bang hi?n t?i'),
            'usable_balance' => $this->decimal()->notNull()->defaultValue('\'0\'')->comment('S? ti?n co th? s? d?ng d? thanh toan'),
            'withdrawable_balance' => $this->decimal()->notNull()->defaultValue('\'0\'')->comment('S? ti?n co th? s? d?ng d? rut kh?i vi'),
            'total_refunded_amount' => $this->decimal()->defaultValue('0')->comment('T?ng s? ti?n d??c refund'),
            'total_topup_amount' => $this->decimal()->defaultValue('0')->comment('T?ng s? ti?n d? n?p'),
            'total_using_amount' => $this->decimal()->defaultValue('0')->comment('T?ng s? ti?n d? thanh toan d?n hang'),
            'total_withdraw_amount' => $this->decimal()->defaultValue('0')->comment('T?ng s? ti?n d? rut'),
            'last_refund_amount' => $this->decimal()->defaultValue('0')->comment('s? ti?n d??c refund l?n cuoi'),
            'last_refund_at' => $this->timestamp()->comment('th?i gian refund l?n cu?i'),
            'last_topup_amount' => $this->decimal()->defaultValue('0')->comment('S? ti?n n?p l?n cuoi'),
            'last_topup_at' => $this->timestamp()->comment('th?i gian n?p l?n cu?i'),
            'last_using_amount' => $this->decimal()->defaultValue('0')->comment('S? ti?n giao d?ch l?n cu?i'),
            'last_using_at' => $this->timestamp()->comment('Th?i gian th?c hi?n giao d?ch cu?i cung'),
            'last_withdraw_amount' => $this->decimal()->defaultValue('0')->comment('S? ti?n rut l?n cu?i'),
            'last_withdraw_at' => $this->timestamp()->comment('Th?i gian rut l?n cu?i'),
            'current_bulk_point' => $this->integer()->defaultValue('0')->comment('S? di?m bulk hi?n t?i'),
            'current_bulk_balance' => $this->decimal()->defaultValue('0')->comment('S? ti?n d??c quy d?i bulk hi?n t?i'),
            'otp_veryfy_code' => $this->string(10)->comment('M? xac th?c otp hi?n t?i'),
            'otp_veryfy_expired_at' => $this->timestamp()->comment('Th?i gian h?t h?n m? otp'),
            'otp_veryfy_count' => $this->integer()->comment('T?ng s? m? xac th?c d? s? d?ng'),
            'store_id' => $this->integer(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'identity_number' => $this->string(20),
            'identity_issued_date' => $this->string(7),
            'identity_issued_by' => $this->string(255),
            'identity_image_url_before' => $this->string(255),
            'identity_verified' => $this->integer()->defaultValue('0'),
            'identity_image_url_after' => $this->string(255),
            'status' => $this->integer()->comment('0:inactive;1active;2:freeze'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%WALLET_CLIENT}}');
    }
}
