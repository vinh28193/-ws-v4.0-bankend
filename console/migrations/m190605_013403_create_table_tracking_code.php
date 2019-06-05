<?php

use yii\db\Migration;

class m190605_013403_create_table_tracking_code extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tracking_code}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY')->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'manifest_id' => $this->integer(11)->comment('Manifest Id'),
            'manifest_code' => $this->string(32)->comment('Manifest code'),
            'delivery_note_id' => $this->integer(11)->comment('Package id after sent'),
            'delivery_note_code' => $this->string(32)->comment('Mã kiện của weshop'),
            'package_id' => $this->integer(11)->comment('Package item id after create item'),
            'tracking_code' => $this->string(255),
            'order_ids' => $this->string(255)->comment('Order id(s)'),
            'weshop_tag' => $this->string(32)->comment('Weshop Tag'),
            'warehouse_us_id' => $this->integer(11),
            'warehouse_us_name' => $this->string(255),
            'warehouse_local_id' => $this->integer(11),
            'warehouse_local_name' => $this->string(32)->comment('warehouse alias BMVN_HN (Boxme Ha Noi/Boxme HCM)'),
            'warehouse_local_tag' => $this->string(32)->comment('warehouse tag'),
            'warehouse_local_note' => $this->text()->comment('warehouse note'),
            'warehouse_local_status' => $this->string(10)->comment('warehouse status (open/close)'),
            'weight' => $this->decimal(10)->defaultValue('0')->comment('seller Weight (kg)'),
            'quantity' => $this->decimal(2)->defaultValue('0')->comment('seller quantity'),
            'dimension_width' => $this->decimal(10)->defaultValue('0')->comment('Width (cm)'),
            'dimension_length' => $this->decimal(10)->defaultValue('0')->comment('Length (cm)'),
            'dimension_height' => $this->decimal(10)->defaultValue('0')->comment('Height (cm)'),
            'operation_note' => $this->text()->comment('Note'),
            'status' => $this->string(32)->defaultValue('NEW')->comment('Status'),
            'remove' => $this->smallInteger(6)->defaultValue('0')->comment('removed or not (1:Removed)'),
            'created_by' => $this->integer(11)->comment('Created by'),
            'created_at' => $this->integer(11)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->comment('Updated by'),
            'updated_at' => $this->integer(11)->comment('Updated at (timestamp)'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'status_merge' => $this->string(255)->comment('Trạng thái của tracking với việc đối chiếu tracking với bảng ext'),
            'stock_in_us' => $this->integer(11),
            'stock_out_us' => $this->integer(11),
            'stock_in_local' => $this->integer(11),
            'stock_out_local' => $this->integer(11),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%tracking_code}}');
    }
}
