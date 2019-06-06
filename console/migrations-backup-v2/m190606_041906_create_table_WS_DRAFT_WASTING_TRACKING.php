<?php

use yii\db\Migration;

class m190606_041906_create_table_WS_DRAFT_WASTING_TRACKING extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%DRAFT_WASTING_TRACKING}}', [
            'id' => $this->integer()->notNull(),
            'tracking_code' => $this->string(255)->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'quantity' => $this->integer(),
            'weight' => $this->decimal(),
            'dimension_l' => $this->decimal(),
            'dimension_w' => $this->decimal(),
            'dimension_h' => $this->decimal(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(255),
            'purchase_invoice_number' => $this->string(255),
            'status' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'item_name' => $this->string(255)->comment('ten s?n ph?m tr? v? t? boxme'),
            'warehouse_tag_boxme' => $this->string(255)->comment('wtag c?a boxme'),
            'note_boxme' => $this->string(255)->comment('note c?a boxme'),
            'image' => $this->text(),
            'type_tracking' => $this->string(255)->comment('split, normal, unknown'),
            'tracking_merge' => $this->text()->comment('List tracking d? d??c merge'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108666C00023$$', '{{%DRAFT_WASTING_TRACKING}}', '', true);
        $this->createIndex('SYS_IL0000108666C00021$$', '{{%DRAFT_WASTING_TRACKING}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%DRAFT_WASTING_TRACKING}}');
    }
}
