<?php

use yii\db\Migration;

class m190605_013401_create_table_draft_extension_tracking_map extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%draft_extension_tracking_map}}', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string(255)->notNull(),
            'product_id' => $this->integer(11)->notNull(),
            'order_id' => $this->integer(11)->notNull(),
            'purchase_invoice_number' => $this->string(255)->notNull(),
            'status' => $this->string(255)->comment('trạng thái của tracking bên us'),
            'quantity' => $this->integer(11),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'number_run' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11),
            'draft_data_tracking_id' => $this->integer(11),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%draft_extension_tracking_map}}');
    }
}
