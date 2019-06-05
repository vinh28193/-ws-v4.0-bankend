<?php

use yii\db\Migration;

class m190605_013402_create_table_purchase_product extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%purchase_product}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(11)->comment('id product mua'),
            'purchase_order_id' => $this->integer(11)->comment('id purchase order'),
            'order_id' => $this->integer(11)->comment('id order'),
            'sku' => $this->string(255)->comment('Mã sku của sản phẩm mua'),
            'product_name' => $this->string(255)->comment('Tên sản phẩm'),
            'image' => $this->string(255)->comment('Ảnh sản phẩm'),
            'purchase_quantity' => $this->integer(11)->comment('số lượng mua'),
            'receive_quantity' => $this->integer(11)->comment('số lượng nhận'),
            'paid_to_seller' => $this->decimal(18, 2)->comment('Số tiền đã thanh toán cho người bán'),
            'changing_price' => $this->decimal(18, 2)->comment('số tiền chênh lệch giá . amount_purchase - amount_order'),
            'type_changing' => $this->string(255)->comment('kiểu chênh lệch: up, down'),
            'purchase_price' => $this->decimal(18, 2)->comment('giá khi đi mua'),
            'purchase_us_tax' => $this->decimal(18, 2)->comment('us tax khi đi mua'),
            'purchase_shipping_fee' => $this->decimal(18, 2)->comment('phí ship khi đi mua'),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'receive_warehouse_id' => $this->integer(11)->comment('Id Kho nhận'),
            'receive_warehouse_name' => $this->string(255)->comment('Tên Kho nhận'),
            'seller_refund_amount' => $this->decimal(18, 2)->comment('Số tiền người bán hoàn chả'),
        ], $tableOptions);

        $this->addForeignKey('fk-purchase-product-order', '{{%purchase_product}}', 'order_id', '{{%order}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk-purchase-product-product', '{{%purchase_product}}', 'product_id', '{{%product}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk-purchase-product-purchase_order', '{{%purchase_product}}', 'purchase_order_id', '{{%purchase_order}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%purchase_product}}');
    }
}
