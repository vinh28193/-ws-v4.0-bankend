<?php

use yii\db\Migration;

class m190606_042928_create_table_WS_WALLET_MERCHANT extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%WALLET_MERCHANT}}', [
            'id' => $this->integer()->notNull(),
            'account_number' => $this->string(50)->notNull()->defaultValue('\'\'')->comment('M? vi - Ma tien to: S(danh cho master)  , n?i b? '),
            'account_email' => $this->string(255)->comment('Email '),
            'account_token' => $this->string(255),
            'account_bank_id' => $this->string(255),
            'parent_account_id' => $this->integer(),
            'description' => $this->string(255)->comment('mo ta ve tai khoan'),
            'opening_balance' => $this->decimal()->comment('S? d? m? vi'),
            'current_balance' => $this->decimal()->comment('S? d? hi?n t?i'),
            'total_credit_amount' => $this->decimal()->comment('T?ng s? giao d?ch phat sinh tang'),
            'total_debit_amount' => $this->decimal()->comment('T?ng s? giao d?ch phat sinh gi?m'),
            'previous_current_balance' => $this->decimal()->comment('S? d? k? tr??c '),
            'last_amount' => $this->decimal(),
            'last_updated' => $this->timestamp(),
            'last_edit_user_id' => $this->integer(),
            'note' => $this->string(500),
            'store_id' => $this->integer(),
            'active' => $this->integer(),
            'account_ref_payment_mapping' => $this->string(255)->comment('M? tai kho?n ngan l??ng / IdoMog mapping tuy thu?c vao StoreId'),
            'payment_provider_id' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%WALLET_MERCHANT}}');
    }
}
