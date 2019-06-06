<?php

use yii\db\Migration;

class m190606_042240_create_table_WS_PAYMENT_TRANSACTION extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PAYMENT_TRANSACTION}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID reference'),
            'customer_id' => $this->integer()->comment('customer id'),
            'transaction_code' => $this->string(32)->comment('m? giao d?ch c?a weshop'),
            'transaction_type' => $this->string(10)->comment('Lo?i giao d?ch: top up , payment, withdraw'),
            'transaction_status' => $this->string(10)->comment('tr?ng thai giao d?ch'),
            'transaction_customer_name' => $this->string(255),
            'transaction_customer_email' => $this->string(255),
            'transaction_customer_phone' => $this->string(255),
            'transaction_customer_address' => $this->string(255),
            'transaction_customer_city' => $this->string(255),
            'transaction_customer_postcode' => $this->string(255),
            'transaction_customer_district' => $this->string(255),
            'transaction_customer_country' => $this->string(255),
            'payment_type' => $this->string(10)->notNull(),
            'payment_provider' => $this->string(50)->notNull(),
            'payment_method' => $this->string(50)->notNull(),
            'payment_bank_code' => $this->string(32),
            'coupon_code' => $this->string(32),
            'used_xu' => $this->integer()->defaultValue('0'),
            'bulk_point' => $this->integer()->defaultValue('0'),
            'carts' => $this->text()->notNull()->comment('list cart'),
            'shipping' => $this->integer()->notNull()->comment('Dia chi ship'),
            'total_discount_amount' => $this->decimal()->defaultValue('0.00'),
            'before_discount_amount_local' => $this->decimal(),
            'transaction_amount_local' => $this->decimal()->comment('s? ti?n giao d?ch, co th? am ho?c d??ng'),
            'transaction_description' => $this->text()->comment('mo t? giao d?ch'),
            'note' => $this->text()->comment('ghi chu c?a nhan vien'),
            'transaction_reference_code' => $this->string(255)->comment('m? tham chi?u thu ti?n , vd : m? v?n d?n thu cod'),
            'third_party_transaction_code' => $this->string(255)->comment('m? giao d?ch v?i ben th? 3. VD: ngan l??ng'),
            'third_party_transaction_link' => $this->text()->comment('Link thanh toan ben th? 3'),
            'third_party_transaction_status' => $this->string(200)->comment('Tr?ng thai thanh toan c?a ben th? 3'),
            'third_party_transaction_time' => $this->integer()->comment('th?i gian giao d?ch ben th? 3'),
            'before_transaction_amount_local' => $this->decimal()->comment('S? ti?n tr??c giao d?ch'),
            'after_transaction_amount_local' => $this->decimal()->comment('S? ti?n sau giao d?ch'),
            'created_at' => $this->integer()->comment('Created at (timestamp)'),
            'topup_transaction_code' => $this->string(32),
            'parent_transaction_code' => $this->string(32),
            'order_code' => $this->string(32),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108746C00027$$', '{{%PAYMENT_TRANSACTION}}', '', true);
        $this->createIndex('SYS_IL0000108746C00028$$', '{{%PAYMENT_TRANSACTION}}', '', true);
        $this->createIndex('SYS_IL0000108746C00022$$', '{{%PAYMENT_TRANSACTION}}', '', true);
        $this->createIndex('SYS_IL0000108746C00031$$', '{{%PAYMENT_TRANSACTION}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%PAYMENT_TRANSACTION}}');
    }
}
