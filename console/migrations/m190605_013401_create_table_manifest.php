<?php

use yii\db\Migration;

class m190605_013401_create_table_manifest extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%manifest}}', [
            'id' => $this->primaryKey(),
            'manifest_code' => $this->string(32)->notNull()->comment('Mã kiện về (từ us/jp ..)'),
            'send_warehouse_id' => $this->integer(11)->comment('Kho gửi đi'),
            'receive_warehouse_id' => $this->integer(11)->comment('Kho nhận'),
            'us_stock_out_time' => $this->dateTime()->comment('ngày xuất kho mỹ'),
            'local_stock_in_time' => $this->dateTime()->comment('ngày nhậo kho việt nam'),
            'local_stock_out_time' => $this->dateTime()->comment('ngày xuất kho'),
            'store_id' => $this->integer(11),
            'created_by' => $this->integer(11)->comment('người tạo'),
            'updated_by' => $this->integer(11),
            'created_at' => $this->integer(11)->comment('ngày tạo'),
            'updated_at' => $this->integer(11),
            'active' => $this->integer(11)->defaultValue('1'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'status' => $this->string(255),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%manifest}}');
    }
}
