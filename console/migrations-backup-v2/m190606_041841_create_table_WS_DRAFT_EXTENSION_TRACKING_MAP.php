<?php

use yii\db\Migration;

class m190606_041841_create_table_WS_DRAFT_EXTENSION_TRACKING_MAP extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%DRAFT_EXTENSION_TRACKING_MAP}}', [
            'id' => $this->integer()->notNull(),
            'tracking_code' => $this->string(255)->notNull(),
            'product_id' => $this->integer()->notNull(),
            'order_id' => $this->integer()->notNull(),
            'purchase_invoice_number' => $this->string(255)->notNull(),
            'status' => $this->string(255)->comment('tr?ng thai c?a tracking ben us'),
            'quantity' => $this->integer(),
            'weight' => $this->decimal(),
            'dimension_l' => $this->decimal(),
            'dimension_w' => $this->decimal(),
            'dimension_h' => $this->decimal(),
            'number_run' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'draft_data_tracking_id' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%DRAFT_EXTENSION_TRACKING_MAP}}');
    }
}
