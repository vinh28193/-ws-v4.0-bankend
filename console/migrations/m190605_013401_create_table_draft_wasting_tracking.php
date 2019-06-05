<?php

use yii\db\Migration;

class m190605_013401_create_table_draft_wasting_tracking extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%draft_wasting_tracking}}', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string(255)->notNull(),
            'product_id' => $this->integer(11),
            'order_id' => $this->integer(11),
            'quantity' => $this->integer(11),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'manifest_id' => $this->integer(11),
            'manifest_code' => $this->string(255),
            'purchase_invoice_number' => $this->string(255),
            'status' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11),
            'item_name' => $this->string(255)->comment('tên sản phẩm trả về từ boxme'),
            'warehouse_tag_boxme' => $this->string(255)->comment('wtag của boxme'),
            'note_boxme' => $this->string(255)->comment('note của boxme'),
            'image' => $this->text(),
            'type_tracking' => $this->string(255)->comment('split, normal, unknown'),
            'tracking_merge' => $this->text()->comment('List tracking đã được merge'),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%draft_wasting_tracking}}');
    }
}
