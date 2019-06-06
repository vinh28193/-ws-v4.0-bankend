<?php

use yii\db\Migration;

class m190606_041814_create_table_WS_DRAFT_BOXME_TRACKING extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%DRAFT_BOXME_TRACKING}}', [
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
            'number_callback' => $this->integer()->comment('S? l?n callback'),
            'status' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'item_name' => $this->string(255)->comment('ten s?n ph?m tr? v? t? boxme'),
            'warehouse_tag_boxme' => $this->string(255)->comment('wtag c?a boxme'),
            'note_boxme' => $this->string(255)->comment('note c?a boxme'),
            'image' => $this->text(),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108646C00022$$', '{{%DRAFT_BOXME_TRACKING}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%DRAFT_BOXME_TRACKING}}');
    }
}
