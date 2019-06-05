<?php

use yii\db\Migration;

class m190605_013401_create_table_coupon extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%coupon}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(255),
            'code' => $this->string(255),
            'message' => $this->string(255)->comment('thông báo khi áp dụng coupon'),
            'type_coupon' => $this->string(255)->comment('REFUND, COUPON ... hệ thống tự sinh, và phải là 1 const'),
            'type_amount' => $this->string(255)->comment('percent,money. nếu là money sẽ lấy theo tiền local.'),
            'store_id' => $this->integer(11),
            'amount' => $this->decimal(18, 2)->comment('đơn vị: % or tiền local. phụ thuộc vào type_amount'),
            'percent_for' => $this->string(255)->comment('tính % cho cái gì. Theo các cel trong bảng tính giá. A1 , A2. Nếu để trống sẽ mặc định tính theo giá tổng'),
            'created_by' => $this->integer(11)->comment('id người tạo'),
            'start_time' => $this->bigInteger(20),
            'end_time' => $this->bigInteger(20),
            'limit_customer_count_use' => $this->integer(11)->comment('giới hạn số lần sử dụng cho 1 user'),
            'limit_count_use' => $this->integer(11)->comment('giới hạn số lần sử dụng'),
            'count_use' => $this->integer(11)->comment('số lần sử dụng'),
            'limit_amount_use' => $this->decimal(18, 2)->comment('giới hạn số tiền tối đa thể sử dụng'),
            'limit_amount_use_order' => $this->decimal(18, 2)->comment('giới hạn số tiền tối đa thể sử dụng cho 1 order'),
            'for_email' => $this->string(255)->comment('coupon cho email nào'),
            'for_portal' => $this->string(255)->comment('coupon cho portal nào'),
            'for_category' => $this->string(255)->comment('coupon cho category nào'),
            'for_min_order_amount' => $this->string(255)->comment('coupon cho giá trị tối thiểu của 1 đơn hàng . đơn vị tiền local'),
            'for_max_order_amount' => $this->string(255)->comment('coupon cho giá trị tối đa của 1 đơn hàng . đơn vị tiền local'),
            'total_amount_used' => $this->decimal(18, 2)->comment('tổng số tiền đã trừ từ coupon này'),
            'used_first_time' => $this->bigInteger(20),
            'used_last_time' => $this->bigInteger(20),
            'can_use_instalment' => $this->tinyInteger(3),
            'created_at' => $this->bigInteger(20),
            'updated_at' => $this->bigInteger(20),
            'remove' => $this->tinyInteger(4),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('idx-coupon-store_id', '{{%coupon}}', 'store_id');
        $this->createIndex('idx-coupon-created_by', '{{%coupon}}', 'created_by');
    }

    public function down()
    {
        $this->dropTable('{{%coupon}}');
    }
}
