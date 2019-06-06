<?php

use yii\db\Migration;

class m190606_042459_create_table_WS_PURCHASE_PRODUCT extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PURCHASE_PRODUCT}}', [
            'id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->comment('id product mua'),
            'purchase_order_id' => $this->integer()->comment('id purchase order'),
            'order_id' => $this->integer()->comment('id order'),
            'sku' => $this->string(255)->comment('M? sku c?a s?n ph?m mua'),
            'product_name' => $this->string(255)->comment('Ten s?n ph?m'),
            'image' => $this->string(255)->comment('?nh s?n ph?m'),
            'purchase_quantity' => $this->integer()->comment('s? l??ng mua'),
            'receive_quantity' => $this->integer()->comment('s? l??ng nh?n'),
            'paid_to_seller' => $this->decimal()->comment('S? ti?n d? thanh toan cho ng??i ban'),
            'changing_price' => $this->decimal()->comment('s? ti?n chenh l?ch gia . amount_purchase - amount_order'),
            'type_changing' => $this->string(255)->comment('ki?u chenh l?ch: up, down'),
            'purchase_price' => $this->decimal()->comment('gia khi di mua'),
            'purchase_us_tax' => $this->decimal()->comment('us tax khi di mua'),
            'purchase_shipping_fee' => $this->decimal()->comment('phi ship khi di mua'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'receive_warehouse_id' => $this->integer()->comment('Id Kho nh?n'),
            'receive_warehouse_name' => $this->string(255)->comment('Ten Kho nh?n'),
            'seller_refund_amount' => $this->decimal()->comment('S? ti?n ng??i ban hoan ch?'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%PURCHASE_PRODUCT}}');
    }
}
