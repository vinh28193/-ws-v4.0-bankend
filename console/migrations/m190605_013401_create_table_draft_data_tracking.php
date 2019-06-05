<?php

use yii\db\Migration;

class m190605_013401_create_table_draft_data_tracking extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%draft_data_tracking}}', [
            'id' => $this->integer(11)->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
            'tracking_code' => $this->string(255)->notNull(),
            'product_id' => $this->integer(11),
            'order_id' => $this->integer(11),
            'manifest_id' => $this->integer(11),
            'manifest_code' => $this->string(255),
            'quantity' => $this->integer(11),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'purchase_invoice_number' => $this->string(255),
            'number_get_detail' => $this->integer(11)->comment('Số lần chạy api lấy detail'),
            'status' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11),
            'type_tracking' => $this->string(255)->comment('split, normal, unknown'),
            'tracking_merge' => $this->text()->comment('List tracking đã được merge'),
            'item_name' => $this->text(),
            'seller_refund_amount' => $this->decimal(18, 2)->comment('Sô tiền seller hoàn'),
            'ws_tracking_code' => $this->string(255)->comment('Mã tracking của weshop'),
            'image' => $this->text(),
            'stock_in_us' => $this->integer(11),
            'stock_out_us' => $this->integer(11),
            'stock_in_local' => $this->integer(11),
            'stock_out_local' => $this->integer(11),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%draft_data_tracking}}');
    }
}
