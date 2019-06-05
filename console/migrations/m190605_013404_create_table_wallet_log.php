<?php

use yii\db\Migration;

class m190605_013404_create_table_wallet_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%wallet_log}}', [
            'id' => $this->primaryKey(),
            'walletTransactionId' => $this->integer(11)->notNull(),
            'TypeTransaction' => $this->string(50)->comment(' TOPUP - nạp tiền
REFUN - nạp tiền do refun
WITHDRAW - rút tiền
FREEZE - đóng băng
UNFREEZEADD - mở đóng băng cộng
UNFREEZEREDUCE - mở đóng băng trừ
PAYMENT - thanh toán '),
            'walletId' => $this->integer(11)->notNull(),
            'typeWallet' => $this->string(255)->comment('CLIENT - ví client
MERCHANT - ví merchant'),
            'description' => $this->text()->notNull(),
            'amount' => $this->decimal(18, 2)->notNull()->defaultValue('0.00')->comment('số tiền giao dịch'),
            'BeforeAccumulatedBalances' => $this->decimal(18, 2)->comment('số dư trước khi giao dịch - theo field current_balance
'),
            'AfterAccumulatedBalances' => $this->decimal(18, 2)->comment('số dư sau khi giao dịch - theo field current_balance'),
            'createDate' => $this->dateTime()->notNull(),
            'storeId' => $this->integer(11),
            'status' => $this->integer(1)->notNull()->defaultValue('0')->comment('0- Pending ; 1 - Success; 2 - Fail'),
        ], $tableOptions);

        /*
        $this->createIndex('index-wallet-transaction', '{{%wallet_log}}', 'walletTransactionId');
        $this->addForeignKey('foreignkey-wallet-transaction', '{{%wallet_log}}', 'walletTransactionId', '{{%wallet_transaction}}', 'id', 'RESTRICT', 'RESTRICT');
        */
    }

    public function down()
    {
        $this->dropTable('{{%wallet_log}}');
    }
}
