<?php

use yii\db\Migration;

class m190605_013403_create_table_shipment_returned extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shipment_returned}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'shipment_code' => $this->integer(11)->comment('mã phiếu giao, BM_CODE'),
            'warehouse_send_id' => $this->integer(11)->comment('id kho gửi đi'),
            'warehouse_tags' => $this->text()->comment('1 list mã thẻ kho Weshop'),
            'customer_id' => $this->integer(11)->comment('id của customer'),
            'shipment_status' => $this->string(255)->comment('trạng thái shipment'),
            'total_weight' => $this->double()->comment('Tổng cân nặng của các món hàng'),
            'total_shipping_fee' => $this->decimal(18, 2)->comment('phí ship'),
            'total_price' => $this->decimal(18, 2)->comment('Tổng giá trị shipment'),
            'total_cod' => $this->decimal(18, 2)->comment('Tổng tiền thu cod'),
            'total_quantity' => $this->integer(11)->comment('Tổng số lượng'),
            'courier_code' => $this->integer(11)->comment('mã hãng vận chuyển'),
            'courier_logo' => $this->text()->comment('logo hãng vận chuyển'),
            'courier_estimate_time' => $this->text()->comment('thời gian ước tính của hãng vận chuyển'),
            'shipment_id' => $this->integer(11),
            'created_at' => $this->bigInteger(20)->comment('thời gian tạo'),
            'updated_at' => $this->bigInteger(20)->comment('thời gian cập nhật'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('idx-shipment_returned-customer_id', '{{%shipment_returned}}', 'customer_id');
        $this->createIndex('idx-shipment_returned-shipment_id', '{{%shipment_returned}}', 'shipment_id');
        $this->createIndex('idx-shipment_returned-warehouse_send_id', '{{%shipment_returned}}', 'warehouse_send_id');
        /*
        $this->addForeignKey('fk-shipment_returned-customer_id', '{{%shipment_returned}}', 'customer_id', '{{%customer}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shipment_returned-shipment_id', '{{%shipment_returned}}', 'shipment_id', '{{%shipment}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-shipment_returned-warehouse_send_id', '{{%shipment_returned}}', 'warehouse_send_id', '{{%warehouse}}', 'id', 'CASCADE', 'CASCADE');
        */
    }

    public function down()
    {
        $this->dropTable('{{%shipment_returned}}');
    }
}
