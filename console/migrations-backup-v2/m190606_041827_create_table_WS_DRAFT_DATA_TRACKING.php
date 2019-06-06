<?php

use yii\db\Migration;

class m190606_041827_create_table_WS_DRAFT_DATA_TRACKING extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%DRAFT_DATA_TRACKING}}', [
            'id' => $this->integer()->notNull(),
            'tracking_code' => $this->string(255)->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(255),
            'quantity' => $this->integer(),
            'weight' => $this->decimal(),
            'dimension_l' => $this->decimal(),
            'dimension_w' => $this->decimal(),
            'dimension_h' => $this->decimal(),
            'purchase_invoice_number' => $this->string(255),
            'number_get_detail' => $this->integer()->comment('S? l?n ch?y api l?y detail'),
            'status' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'type_tracking' => $this->string(255)->comment('split, normal, unknown'),
            'tracking_merge' => $this->text()->comment('List tracking d? d??c merge'),
            'item_name' => $this->text(),
            'seller_refund_amount' => $this->decimal()->comment('So ti?n seller hoan'),
            'ws_tracking_code' => $this->string(255)->comment('M? tracking c?a weshop'),
            'image' => $this->text(),
            'stock_in_us' => $this->integer(),
            'stock_out_us' => $this->integer(),
            'stock_in_local' => $this->integer(),
            'stock_out_local' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108650C00021$$', '{{%DRAFT_DATA_TRACKING}}', '', true);
        $this->createIndex('SYS_IL0000108650C00024$$', '{{%DRAFT_DATA_TRACKING}}', '', true);
        $this->createIndex('SYS_IL0000108650C00020$$', '{{%DRAFT_DATA_TRACKING}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%DRAFT_DATA_TRACKING}}');
    }
}
