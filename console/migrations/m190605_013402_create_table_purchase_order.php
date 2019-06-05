<?php

use yii\db\Migration;

class m190605_013402_create_table_purchase_order extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%purchase_order}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'note' => $this->string(255)->comment('Ghi chú đơn hàng'),
            'purchase_order_number' => $this->string(255)->comment('mã đơn hàng trên ebay,amazon ...'),
            'total_item' => $this->decimal(18, 2)->comment('Tổng số lượng item có'),
            'total_quantity' => $this->decimal(18, 2)->comment('Tổng số lượng'),
            'total_paid_seller' => $this->decimal(18, 2)->comment('Tiền đã thanh toán cho người bán. Đơn vị: usd, jpy .v.v.'),
            'total_changing_price' => $this->decimal(18, 2)->comment('số tiền chênh lệch giá . amount_purchase - amount_order'),
            'total_type_changing' => $this->string(255)->comment('kiểu chênh lệch: up, down'),
            'receive_warehouse_id' => $this->integer(11)->comment('Id kho nhận hàng.'),
            'purchase_account_id' => $this->integer(11)->comment('id tài khoản mua hàng.'),
            'purchase_card_id' => $this->integer(11)->comment('id thẻ mua thanh toán.'),
            'purchase_card_number' => $this->string(255)->comment('số thẻ thanh toán.'),
            'purchase_amount_buck' => $this->decimal(10)->comment('số tiền buck thành toán.'),
            'transaction_payment' => $this->string(255)->comment('Mã giao dịch thanh toán paypal.'),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'updated_by' => $this->integer(11)->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%purchase_order}}');
    }
}
