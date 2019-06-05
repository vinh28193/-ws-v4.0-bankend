<?php

use yii\db\Migration;

class m190605_013402_create_table_shipment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shipment}}', [
            'id' => $this->primaryKey(),
            'shipment_code' => $this->string(32)->comment('mã phiếu giao, BM_CODE'),
            'warehouse_tags' => $this->text()->comment('1 list mã thẻ kho Weshop'),
            'total_weight' => $this->double()->comment('Tổng cân nặng của các món hàng'),
            'warehouse_send_id' => $this->integer(11)->comment('id kho gửi đi'),
            'customer_id' => $this->integer(11)->comment('id của customer'),
            'receiver_email' => $this->string(255),
            'receiver_name' => $this->string(255),
            'receiver_phone' => $this->string(255),
            'receiver_address' => $this->string(255),
            'receiver_country_id' => $this->integer(11),
            'receiver_country_name' => $this->string(255),
            'receiver_province_id' => $this->integer(11),
            'receiver_province_name' => $this->string(255),
            'receiver_district_id' => $this->integer(11),
            'receiver_district_name' => $this->string(255),
            'receiver_post_code' => $this->string(255),
            'receiver_address_id' => $this->integer(11)->comment('id address của người nhận trong bảng address'),
            'note_by_customer' => $this->text()->comment('Ghi chú của customer'),
            'note' => $this->text()->comment('Ghi chú cho đơn hàng'),
            'shipment_status' => $this->string(255)->comment('trạng thái shipment'),
            'total_shipping_fee' => $this->decimal(18, 2)->comment('phí ship'),
            'total_price' => $this->decimal(18, 2)->comment('Tổng giá trị shipment'),
            'total_cod' => $this->decimal(18, 2)->comment('Tổng tiền thu cod'),
            'total_quantity' => $this->integer(11)->comment('Tổng số lượng'),
            'is_hold' => $this->integer(11)->comment('đánh dấu hàng hold, 0 là không hold, 1 là hold'),
            'is_insurance' => $this->smallInteger(6)->defaultValue('0')->comment('đánh dấu bảo hiểm'),
            'courier_code' => $this->string(32)->comment('mã hãng vận chuyển'),
            'courier_logo' => $this->text()->comment('mã hãng vận chuyển'),
            'courier_estimate_time' => $this->text()->comment('thời gian ước tính của hãng vận chuyển'),
            'list_old_shipment_code' => $this->text()->comment('danh sách mã shipment cũ đã bị cancel'),
            'created_at' => $this->bigInteger(20)->comment('thời gian tạo'),
            'updated_at' => $this->bigInteger(20)->comment('thời gian cập nhật'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'active' => $this->integer(11)->defaultValue('1'),
            'shipment_send_at' => $this->integer(11),
        ], $tableOptions);

        /*
        $this->createIndex('idx-shipment-customer_id', '{{%shipment}}', 'customer_id');
        $this->createIndex('idx-shipment-receiver_province_id', '{{%shipment}}', 'receiver_province_id');
        $this->createIndex('idx-shipment-warehouse_send_id', '{{%shipment}}', 'warehouse_send_id');
        $this->createIndex('idx-shipment-receiver_district_id', '{{%shipment}}', 'receiver_district_id');
        $this->createIndex('idx-shipment-receiver_address_id', '{{%shipment}}', 'receiver_address_id');
        $this->createIndex('idx-shipment-receiver_country_id', '{{%shipment}}', 'receiver_country_id');
        /*
        $this->addForeignKey('fk-shipment-receiver_province_id', '{{%shipment}}', 'receiver_province_id', '{{%system_state_province}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shipment-warehouse_send_id', '{{%shipment}}', 'warehouse_send_id', '{{%warehouse}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shipment-customer_id', '{{%shipment}}', 'customer_id', '{{%customer}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shipment-receiver_address_id', '{{%shipment}}', 'receiver_address_id', '{{%address}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shipment-receiver_country_id', '{{%shipment}}', 'receiver_country_id', '{{%system_country}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shipment-receiver_district_id', '{{%shipment}}', 'receiver_district_id', '{{%system_district}}', 'id', 'CASCADE', 'CASCADE');
        */
    }

    public function down()
    {
        $this->dropTable('{{%shipment}}');
    }
}
