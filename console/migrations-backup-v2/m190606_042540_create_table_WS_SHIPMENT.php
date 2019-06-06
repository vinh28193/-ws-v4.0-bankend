<?php

use yii\db\Migration;

class m190606_042540_create_table_WS_SHIPMENT extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SHIPMENT}}', [
            'id' => $this->integer()->notNull(),
            'shipment_code' => $this->string(32)->comment('m? phi?u giao, BM_CODE'),
            'warehouse_tags' => $this->text()->comment('1 list m? th? kho Weshop'),
            'total_weight' => $this->decimal()->comment('T?ng can n?ng c?a cac mon hang'),
            'warehouse_send_id' => $this->integer()->comment('id kho g?i di'),
            'customer_id' => $this->integer()->comment('id c?a customer'),
            'receiver_email' => $this->string(255),
            'receiver_name' => $this->string(255),
            'receiver_phone' => $this->string(255),
            'receiver_address' => $this->string(255),
            'receiver_country_id' => $this->integer(),
            'receiver_country_name' => $this->string(255),
            'receiver_province_id' => $this->integer(),
            'receiver_province_name' => $this->string(255),
            'receiver_district_id' => $this->integer(),
            'receiver_district_name' => $this->string(255),
            'receiver_post_code' => $this->string(255),
            'receiver_address_id' => $this->integer()->comment('id address c?a ng??i nh?n trong b?ng address'),
            'note_by_customer' => $this->text()->comment('Ghi chu c?a customer'),
            'note' => $this->text()->comment('Ghi chu cho d?n hang'),
            'shipment_status' => $this->string(255)->comment('tr?ng thai shipment'),
            'total_shipping_fee' => $this->decimal()->comment('phi ship'),
            'total_price' => $this->decimal()->comment('T?ng gia tr? shipment'),
            'total_cod' => $this->decimal()->comment('T?ng ti?n thu cod'),
            'total_quantity' => $this->integer()->comment('T?ng s? l??ng'),
            'is_hold' => $this->integer()->comment('danh d?u hang hold, 0 la khong hold, 1 la hold'),
            'is_insurance' => $this->integer()->defaultValue('0')->comment('danh d?u b?o hi?m'),
            'courier_code' => $this->string(32)->comment('m? h?ng v?n chuy?n'),
            'courier_logo' => $this->text()->comment('m? h?ng v?n chuy?n'),
            'courier_estimate_time' => $this->text()->comment('th?i gian ??c tinh c?a h?ng v?n chuy?n'),
            'list_old_shipment_code' => $this->text()->comment('danh sach m? shipment c? d? b? cancel'),
            'created_at' => $this->integer()->comment('th?i gian t?o'),
            'updated_at' => $this->integer()->comment('th?i gian c?p nh?t'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'active' => $this->integer()->defaultValue('1'),
            'shipment_send_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108808C00003$$', '{{%SHIPMENT}}', '', true);
        $this->createIndex('SYS_IL0000108808C00030$$', '{{%SHIPMENT}}', '', true);
        $this->createIndex('SYS_IL0000108808C00019$$', '{{%SHIPMENT}}', '', true);
        $this->createIndex('SYS_IL0000108808C00029$$', '{{%SHIPMENT}}', '', true);
        $this->createIndex('SYS_IL0000108808C00031$$', '{{%SHIPMENT}}', '', true);
        $this->createIndex('SYS_IL0000108808C00020$$', '{{%SHIPMENT}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%SHIPMENT}}');
    }
}
