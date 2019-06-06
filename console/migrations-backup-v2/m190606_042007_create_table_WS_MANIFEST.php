<?php

use yii\db\Migration;

class m190606_042007_create_table_WS_MANIFEST extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%MANIFEST}}', [
            'id' => $this->integer()->notNull(),
            'manifest_code' => $this->string(32)->notNull()->comment('M? ki?n v? (t? us/jp ..)'),
            'send_warehouse_id' => $this->integer()->comment('Kho g?i di'),
            'receive_warehouse_id' => $this->integer()->comment('Kho nh?n'),
            'us_stock_out_time' => $this->timestamp()->comment('ngay xu?t kho m?'),
            'local_stock_in_time' => $this->timestamp()->comment('ngay nh?o kho vi?t nam'),
            'local_stock_out_time' => $this->timestamp()->comment('ngay xu?t kho'),
            'store_id' => $this->integer(),
            'created_by' => $this->integer()->comment('ng??i t?o'),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer()->comment('ngay t?o'),
            'updated_at' => $this->integer(),
            'active' => $this->integer()->defaultValue('1'),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'status' => $this->string(255),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%MANIFEST}}');
    }
}
