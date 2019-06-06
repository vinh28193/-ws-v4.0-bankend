<?php

use yii\db\Migration;

class m190606_042915_create_table_WS_WALLET_LOG extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%WALLET_LOG}}', [
            'id' => $this->integer()->notNull(),
            'wallettransactionid' => $this->integer()->notNull(),
            'typetransaction' => $this->string(50)->comment(' TOPUP - n?p ti?n
REFUN - n?p ti?n do refun
WITHDRAW - rut ti?n
FREEZE - dong bang
UNFREEZEADD - m? dong bang c?ng
UNFREEZEREDUCE - m? dong bang tr?
PAYMENT - thanh toan '),
            'walletid' => $this->integer()->notNull(),
            'typewallet' => $this->string(255)->comment('CLIENT - vi client
MERCHANT - vi merchant'),
            'description' => $this->text()->notNull(),
            'amount' => $this->decimal()->notNull()->defaultValue('\'0.00\'')->comment('s? ti?n giao d?ch'),
            'beforeaccumulatedbalances' => $this->decimal()->comment('s? d? tr??c khi giao d?ch - theo field current_balance
'),
            'afteraccumulatedbalances' => $this->decimal()->comment('s? d? sau khi giao d?ch - theo field current_balance'),
            'createdate' => $this->timestamp()->notNull(),
            'storeid' => $this->integer(),
            'status' => $this->integer()->notNull()->defaultValue('0')->comment('0- Pending ; 1 - Success; 2 - Fail'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108870C00006$$', '{{%WALLET_LOG}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%WALLET_LOG}}');
    }
}
