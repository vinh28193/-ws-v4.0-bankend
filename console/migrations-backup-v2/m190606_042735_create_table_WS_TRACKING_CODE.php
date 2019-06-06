<?php

use yii\db\Migration;

class m190606_042735_create_table_WS_TRACKING_CODE extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%TRACKING_CODE}}', [
            'id' => $this->integer()->notNull()->comment('ID'),
            'store_id' => $this->integer()->notNull()->comment('Store ID reference'),
            'manifest_id' => $this->integer()->comment('Manifest Id'),
            'manifest_code' => $this->string(32)->comment('Manifest code'),
            'delivery_note_id' => $this->integer()->comment('Package id after sent'),
            'delivery_note_code' => $this->string(32)->comment('M? ki?n c?a weshop'),
            'package_id' => $this->integer()->comment('Package item id after create item'),
            'tracking_code' => $this->string(255),
            'order_ids' => $this->string(255)->comment('Order id(s)'),
            'weshop_tag' => $this->string(32)->comment('Weshop Tag'),
            'warehouse_us_id' => $this->integer(),
            'warehouse_us_name' => $this->string(255),
            'warehouse_local_id' => $this->integer(),
            'warehouse_local_name' => $this->string(32)->comment('warehouse alias BMVN_HN (Boxme Ha Noi/Boxme HCM)'),
            'warehouse_local_tag' => $this->string(32)->comment('warehouse tag'),
            'warehouse_local_note' => $this->text()->comment('warehouse note'),
            'warehouse_local_status' => $this->string(10)->comment('warehouse status (open/close)'),
            'weight' => $this->decimal()->defaultValue('0')->comment('seller Weight (kg)'),
            'quantity' => $this->decimal()->defaultValue('0')->comment('seller quantity'),
            'dimension_width' => $this->decimal()->defaultValue('0')->comment('Width (cm)'),
            'dimension_length' => $this->decimal()->defaultValue('0')->comment('Length (cm)'),
            'dimension_height' => $this->decimal()->defaultValue('0')->comment('Height (cm)'),
            'operation_note' => $this->text()->comment('Note'),
            'status' => $this->string(32)->defaultValue('NEW')->comment('Status'),
            'remove' => $this->integer()->defaultValue('0')->comment('removed or not (1:Removed)'),
            'created_by' => $this->integer()->comment('Created by'),
            'created_at' => $this->integer()->comment('Created at (timestamp)'),
            'updated_by' => $this->integer()->comment('Updated by'),
            'updated_at' => $this->integer()->comment('Updated at (timestamp)'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'status_merge' => $this->string(255)->comment('Tr?ng thai c?a tracking v?i vi?c d?i chi?u tracking v?i b?ng ext'),
            'stock_in_us' => $this->integer(),
            'stock_out_us' => $this->integer(),
            'stock_in_local' => $this->integer(),
            'stock_out_local' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108836C00016$$', '{{%TRACKING_CODE}}', '', true);
        $this->createIndex('SYS_IL0000108836C00023$$', '{{%TRACKING_CODE}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%TRACKING_CODE}}');
    }
}
