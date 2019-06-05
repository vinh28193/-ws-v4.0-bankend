<?php

use yii\db\Migration;

class m190605_013402_create_table_delivery_note extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%delivery_note}}', [
            'id' => $this->primaryKey(),
            'delivery_note_code' => $this->string(32)->comment('Mã kiện của weshop'),
            'tracking_seller' => $this->string(255)->comment('mã giao dịch của weshop'),
            'order_ids' => $this->text()->comment('List mã order cách nhau bằng dấu ,'),
            'tracking_reference_1' => $this->text()->comment('mã tracking tham chiếu 1'),
            'tracking_reference_2' => $this->text()->comment('mã tracking tham chiếu 2'),
            'manifest_code' => $this->text()->comment('mã lô hàng'),
            'delivery_note_weight' => $this->double()->comment('cân nặng tịnh của cả gói , đơn vị gram'),
            'delivery_note_change_weight' => $this->double()->comment('cân nặng quy đổi của cả gói , đơn vị gram'),
            'delivery_note_dimension_l' => $this->double()->comment('chiều dài của cả gói , đơn vị cm'),
            'delivery_note_dimension_w' => $this->double()->comment('chiều rộng của cả gói , đơn vị cm'),
            'delivery_note_dimension_h' => $this->double()->comment('chiều cao của cả gói , đơn vị cm'),
            'seller_shipped' => $this->bigInteger(20),
            'stock_in_us' => $this->bigInteger(20),
            'stock_out_us' => $this->bigInteger(20),
            'stock_in_local' => $this->bigInteger(20),
            'lost' => $this->bigInteger(20),
            'current_status' => $this->string(100),
            'warehouse_id' => $this->integer(11)->comment('id kho nhận'),
            'created_at' => $this->bigInteger(20)->comment('thời gian tạo'),
            'updated_at' => $this->bigInteger(20)->comment('thời gian cập nhật'),
            'remove' => $this->integer(11)->defaultValue('0'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'shipment_id' => $this->integer(11),
            'customer_id' => $this->integer(11),
            'receiver_address_id' => $this->integer(11)->comment('id địa chỉ nhận của khách'),
            'receiver_name' => $this->string(255),
            'receiver_email' => $this->string(255),
            'receiver_phone' => $this->string(255),
            'receiver_address' => $this->string(255),
            'receiver_country_id' => $this->integer(11),
            'receiver_country_name' => $this->string(255),
            'receiver_province_id' => $this->integer(11),
            'receiver_province_name' => $this->string(255),
            'receiver_district_id' => $this->integer(11),
            'receiver_district_name' => $this->string(255),
            'receiver_post_code' => $this->string(255),
            'insurance' => $this->integer(11)->defaultValue('0')->comment('0: auto, 1: insurance, 2: unInsurance'),
            'pack_wood' => $this->integer(11)->defaultValue('0')->comment('0: unInsurance, 1: insurance'),
        ], $tableOptions);

        // $this->createIndex('idx-package-warehouse_id', '{{%delivery_note}}', 'warehouse_id');
        // $this->addForeignKey('fk-package-warehouse_id', '{{%delivery_note}}', 'warehouse_id', '{{%warehouse}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%delivery_note}}');
    }
}
