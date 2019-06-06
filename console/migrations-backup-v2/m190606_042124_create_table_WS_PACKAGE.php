<?php

use yii\db\Migration;

class m190606_042124_create_table_WS_PACKAGE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PACKAGE}}', [
            'id' => $this->integer()->notNull(),
            'tracking_code' => $this->string(255)->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'quantity' => $this->integer(),
            'weight' => $this->decimal(),
            'dimension_l' => $this->decimal(),
            'dimension_w' => $this->decimal(),
            'dimension_h' => $this->decimal(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(255),
            'purchase_invoice_number' => $this->string(255),
            'status' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'item_name' => $this->string(255)->comment('ten s?n ph?m tr? v? t? boxme'),
            'warehouse_tag_boxme' => $this->string(255)->comment('wtag c?a boxme'),
            'note_boxme' => $this->string(255)->comment('note c?a boxme'),
            'image' => $this->text(),
            'tracking_merge' => $this->text()->comment(' List tracking khi merge t? th?a va thi?u '),
            'hold' => $this->integer()->comment('Danh d?u hang hold. 1 la hold'),
            'type_tracking' => $this->string(255)->comment('split, normal, unknown'),
            'seller_refund_amount' => $this->decimal()->comment('So ti?n seller hoan'),
            'draft_data_tracking_id' => $this->integer(),
            'stock_in_local' => $this->integer()->comment('Th?i gian nh?p kho local'),
            'stock_out_local' => $this->integer()->comment('Th?i gian xu?t kho local'),
            'at_customer' => $this->integer()->comment('Th?i gian t?i tay khach hang'),
            'returned' => $this->integer()->comment('Th?i gian hoan tr?'),
            'lost' => $this->integer()->comment('Th?i gian m?t hang'),
            'current_status' => $this->string(255)->comment('Tr?ng thai hi?n t?i'),
            'shipment_id' => $this->integer(),
            'remove' => $this->integer()->defaultValue('0')->comment('Xoa'),
            'price' => $this->decimal()->comment('Gia tr? c?a 1 s?n ph?m'),
            'cod' => $this->decimal()->comment('Ti?n cod'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('Version'),
            'delivery_note_id' => $this->integer(),
            'delivery_note_code' => $this->string(32),
            'ws_tracking_code' => $this->string(255)->comment('M? tracking c?a weshop'),
            'package_code' => $this->string(255),
            'stock_in_us' => $this->integer(),
            'stock_out_us' => $this->integer(),
            'insurance' => $this->integer()->defaultValue('0')->comment('0: auto, 1: insurance, 2: unInsurance'),
            'pack_wood' => $this->integer()->defaultValue('0')->comment('0: unInsurance, 1: insurance'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108694C00022$$', '{{%PACKAGE}}', '', true);
        $this->createIndex('SYS_IL0000108694C00021$$', '{{%PACKAGE}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%PACKAGE}}');
    }
}
