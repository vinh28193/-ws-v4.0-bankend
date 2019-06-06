<?php

use yii\db\Migration;

class m190606_042556_create_table_WS_SHIPMENT_RETURNED extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%SHIPMENT_RETURNED}}', [
            'id' => $this->integer()->notNull(),
            'shipment_code' => $this->integer()->comment('m? phi?u giao, BM_CODE'),
            'warehouse_send_id' => $this->integer()->comment('id kho g?i di'),
            'warehouse_tags' => $this->text()->comment('1 list m? th? kho Weshop'),
            'customer_id' => $this->integer()->comment('id c?a customer'),
            'shipment_status' => $this->string(255)->comment('tr?ng thai shipment'),
            'total_weight' => $this->decimal()->comment('T?ng can n?ng c?a cac mon hang'),
            'total_shipping_fee' => $this->decimal()->comment('phi ship'),
            'total_price' => $this->decimal()->comment('T?ng gia tr? shipment'),
            'total_cod' => $this->decimal()->comment('T?ng ti?n thu cod'),
            'total_quantity' => $this->integer()->comment('T?ng s? l??ng'),
            'courier_code' => $this->integer()->comment('m? h?ng v?n chuy?n'),
            'courier_logo' => $this->text()->comment('logo h?ng v?n chuy?n'),
            'courier_estimate_time' => $this->text()->comment('th?i gian ??c tinh c?a h?ng v?n chuy?n'),
            'shipment_id' => $this->integer(),
            'created_at' => $this->integer()->comment('th?i gian t?o'),
            'updated_at' => $this->integer()->comment('th?i gian c?p nh?t'),
            'version' => $this->string(255)->defaultValue('\'4.0\'')->comment('version 4.0'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108822C00004$$', '{{%SHIPMENT_RETURNED}}', '', true);
        $this->createIndex('SYS_IL0000108822C00013$$', '{{%SHIPMENT_RETURNED}}', '', true);
        $this->createIndex('SYS_IL0000108822C00014$$', '{{%SHIPMENT_RETURNED}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%SHIPMENT_RETURNED}}');
    }
}
