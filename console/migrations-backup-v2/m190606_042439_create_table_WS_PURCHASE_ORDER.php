<?php

use yii\db\Migration;

class m190606_042439_create_table_WS_PURCHASE_ORDER extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PURCHASE_ORDER}}', [
            'id' => $this->integer()->notNull(),
            'note' => $this->string(255)->comment('Ghi chu d?n hang'),
            'purchase_order_number' => $this->string(255)->comment('m? d?n hang tren ebay,amazon ...'),
            'total_item' => $this->decimal()->comment('T?ng s? l??ng item co'),
            'total_quantity' => $this->decimal()->comment('T?ng s? l??ng'),
            'total_paid_seller' => $this->decimal()->comment('Ti?n d? thanh toan cho ng??i ban. D?n v?: usd, jpy .v.v.'),
            'total_changing_price' => $this->decimal()->comment('s? ti?n chenh l?ch gia . amount_purchase - amount_order'),
            'total_type_changing' => $this->string(255)->comment('ki?u chenh l?ch: up, down'),
            'receive_warehouse_id' => $this->integer()->comment('Id kho nh?n hang.'),
            'purchase_account_id' => $this->integer()->comment('id tai kho?n mua hang.'),
            'purchase_card_id' => $this->integer()->comment('id th? mua thanh toan.'),
            'purchase_card_number' => $this->string(255)->comment('s? th? thanh toan.'),
            'purchase_amount_buck' => $this->decimal()->comment('s? ti?n buck thanh toan.'),
            'transaction_payment' => $this->string(255)->comment('M? giao d?ch thanh toan paypal.'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%PURCHASE_ORDER}}');
    }
}
