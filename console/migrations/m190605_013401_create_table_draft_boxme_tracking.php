<?php

use yii\db\Migration;

class m190605_013401_create_table_draft_boxme_tracking extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%draft_boxme_tracking}}', [
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
            'number_callback' => $this->integer(11)->comment('Số lần callback'),
            'status' => $this->string(255),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11),
            'item_name' => $this->string(255)->comment('tên sản phẩm trả về từ boxme'),
            'warehouse_tag_boxme' => $this->string(255)->comment('wtag của boxme'),
            'note_boxme' => $this->string(255)->comment('note của boxme'),
            'image' => $this->text(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%draft_boxme_tracking}}');
    }
}
