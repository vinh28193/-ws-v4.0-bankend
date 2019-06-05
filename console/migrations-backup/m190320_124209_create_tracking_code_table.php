<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `tracking_code`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190320_124209_create_tracking_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tracking_code', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'manifest_id' => $this->integer(11)->null()->comment('Manifest Id'),
            'manifest_code' => $this->string(32)->null()->comment('Manifest code'),
            'package_id' => $this->integer(11)->defaultValue(null)->comment("Package id after sent"),
            'package_code' => $this->integer(11)->defaultValue(null)->comment("Package code after sent"),
            'package_item_id' => $this->integer(11)->null()->defaultValue(null)->comment("Package item id after create item"),
            'tracking_code' => $this->string(32)->null()->comment('Tracking code'),
            'order_ids' => $this->integer(11)->null()->defaultValue(null)->comment('Order id(s)'),
            'weshop_tag' => $this->string(32)->null()->defaultValue(null)->comment("Weshop Tag"),
            'warehouse_alias' => $this->string(32)->null()->comment('warehouse alias BMVN_HN (Boxme Ha Noi/Boxme HCM)'),
            'warehouse_tag' => $this->string(32)->null()->comment('warehouse tag'),
            'warehouse_note' => $this->text()->null()->comment('warehouse note'),
            'warehouse_status' => $this->string(10)->null()->comment('warehouse status (open/close)'),
            'weight' => $this->decimal(2)->defaultValue(0.00)->comment('seller Weight (kg)'),
            'quantity' => $this->decimal(2)->defaultValue(0.00)->comment('seller quantity'),
            'dimension_width' => $this->decimal(2)->defaultValue(0.00)->comment('Width (cm)'),
            'dimension_length' => $this->decimal(2)->defaultValue(0.00)->comment('Length (cm)'),
            'dimension_height' => $this->decimal(2)->defaultValue(0.00)->comment('Height (cm)'),
            'operation_note' => $this->text()->null()->comment('Note'),
            'status' => $this->string(32)->defaultValue('NEW')->comment('Status'),
            'remove' => $this->smallInteger()->defaultValue(0)->comment('removed or not (1:Removed)'),
            'created_by' => $this->integer(11)->defaultValue(null)->comment('Created by'),
            'created_at' => $this->integer(11)->defaultValue(null)->comment('Created at (timestamp)'),
            'updated_by' => $this->integer(11)->defaultValue(null)->comment('Updated by'),
            'updated_at' => $this->integer(11)->defaultValue(null)->comment('Updated at (timestamp)'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropTable('tracking_code');
    }
}
