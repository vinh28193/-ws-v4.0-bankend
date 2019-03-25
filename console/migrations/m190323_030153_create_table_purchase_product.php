<?php

use yii\db\Migration;

/**
 * Class m190323_030153_create_table_purchase_product
 */
class m190323_030153_create_table_purchase_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('purchase_order',[
            'id' => $this->primaryKey(),
            'note' => $this->string()->comment("Ghi chú đơn hàng"),
            'purchase_order_number' => $this->string()->comment("mã đơn hàng trên ebay,amazon ..."),
            'total_item' => $this->decimal(18,2)->comment("Tổng số lượng item có"),
            'total_quantity' => $this->decimal(18,2)->comment("Tổng số lượng"),
            'total_paid_seller' => $this->decimal(18,2)->comment("Tiền đã thanh toán cho người bán. Đơn vị: usd, jpy .v.v."),
            'total_changing_price' => $this->decimal(18,2)->comment("số tiền chênh lệch giá . amount_purchase - amount_order"),
            'total_type_changing' => $this->decimal(18,2)->comment("kiểu chênh lệch: up, down"),
            'receive_warehouse_id' => $this->integer()->comment("Id kho nhận hàng."),
            'purchase_account_id' => $this->integer()->comment("id tài khoản mua hàng."),
            'purchase_card_id' => $this->integer()->comment("id thẻ mua thanh toán."),
            'purchase_card_number' => $this->string()->comment("số thẻ thanh toán."),
            'purchase_amount_buck' => $this->decimal()->comment("số tiền buck thành toán."),
            'transaction_payment' => $this->decimal()->comment("Mã giao dịch thanh toán paypal."),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        $this->createTable('purchase_product',[
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->comment("id product mua"),
            'purchase_order_id' => $this->integer()->comment("id purchase order"),
            'order_id' => $this->integer()->comment("id order"),
            'sku' => $this->string()->comment("Mã sku của sản phẩm mua"),
            'product_name' => $this->string()->comment("Tên sản phẩm"),
            'image' => $this->string()->comment("Ảnh sản phẩm"),
            'purchase_quantity' => $this->integer()->comment("số lượng mua"),
            'receive_quantity' => $this->integer()->comment("số lượng nhận"),
            'paid_to_seller' => $this->decimal(18,2)->comment("Số tiền đã thanh toán cho người bán"),
            'changing_price' => $this->decimal(18,2)->comment("số tiền chênh lệch giá . amount_purchase - amount_order"),
            'type_changing' => $this->decimal(18,2)->comment("kiểu chênh lệch: up, down"),
            'purchase_price' => $this->decimal(18,2)->comment("giá khi đi mua"),
            'purchase_us_tax' => $this->decimal(18,2)->comment("us tax khi đi mua"),
            'purchase_shipping_fee' => $this->decimal(18,2)->comment("phí ship khi đi mua"),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey('fk-purchase_product-product','purchase_product','product_id','product','id');
        $this->addForeignKey('fk-purchase_product-purchase_order','purchase_product','purchase_order_id','purchase_order','id');
        $this->addForeignKey('fk-purchase_product-order','purchase_product','order_id','order','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190323_030153_create_table_purchase_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190323_030153_create_table_purchase_product cannot be reverted.\n";

        return false;
    }
    */
}
