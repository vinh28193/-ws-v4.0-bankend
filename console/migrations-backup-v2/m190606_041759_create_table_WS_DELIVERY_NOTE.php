<?php

use yii\db\Migration;

class m190606_041759_create_table_WS_DELIVERY_NOTE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%DELIVERY_NOTE}}', [
            'id' => $this->integer()->notNull(),
            'delivery_note_code' => $this->string(32)->comment('M? ki?n c?a weshop'),
            'tracking_seller' => $this->string(255)->comment('m? giao d?ch c?a weshop'),
            'order_ids' => $this->text()->comment('List m? order cach nhau b?ng d?u ,'),
            'tracking_reference_1' => $this->text()->comment('m? tracking tham chi?u 1'),
            'tracking_reference_2' => $this->text()->comment('m? tracking tham chi?u 2'),
            'manifest_code' => $this->text()->comment('m? lo hang'),
            'delivery_note_weight' => $this->decimal()->comment('can n?ng t?nh c?a c? goi , d?n v? gram'),
            'delivery_note_change_weight' => $this->decimal()->comment('can n?ng quy d?i c?a c? goi , d?n v? gram'),
            'delivery_note_dimension_l' => $this->decimal()->comment('chi?u dai c?a c? goi , d?n v? cm'),
            'delivery_note_dimension_w' => $this->decimal()->comment('chi?u r?ng c?a c? goi , d?n v? cm'),
            'delivery_note_dimension_h' => $this->decimal()->comment('chi?u cao c?a c? goi , d?n v? cm'),
            'seller_shipped' => $this->integer(),
            'stock_in_us' => $this->integer(),
            'stock_out_us' => $this->integer(),
            'stock_in_local' => $this->integer(),
            'lost' => $this->integer(),
            'current_status' => $this->string(100),
            'warehouse_id' => $this->integer()->comment('id kho nh?n'),
            'created_at' => $this->integer()->comment('th?i gian t?o'),
            'updated_at' => $this->integer()->comment('th?i gian c?p nh?t'),
            'remove' => $this->integer()->defaultValue('0'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'shipment_id' => $this->integer(),
            'customer_id' => $this->integer(),
            'receiver_address_id' => $this->integer()->comment('id d?a ch? nh?n c?a khach'),
            'receiver_name' => $this->string(255),
            'receiver_email' => $this->string(255),
            'receiver_phone' => $this->string(255),
            'receiver_address' => $this->string(255),
            'receiver_country_id' => $this->integer(),
            'receiver_country_name' => $this->string(255),
            'receiver_province_id' => $this->integer(),
            'receiver_province_name' => $this->string(255),
            'receiver_district_id' => $this->integer(),
            'receiver_district_name' => $this->string(255),
            'receiver_post_code' => $this->string(255),
            'insurance' => $this->integer()->defaultValue('0')->comment('0: auto, 1: insurance, 2: unInsurance'),
            'pack_wood' => $this->integer()->defaultValue('0')->comment('0: unInsurance, 1: insurance'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108712C00005$$', '{{%DELIVERY_NOTE}}', '', true);
        $this->createIndex('SYS_IL0000108712C00006$$', '{{%DELIVERY_NOTE}}', '', true);
        $this->createIndex('SYS_IL0000108712C00004$$', '{{%DELIVERY_NOTE}}', '', true);
        $this->createIndex('SYS_IL0000108712C00007$$', '{{%DELIVERY_NOTE}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%DELIVERY_NOTE}}');
    }
}
