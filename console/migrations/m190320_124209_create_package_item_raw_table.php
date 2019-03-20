<?php

use common\components\db\Migration;

/**
 * Handles the creation of table `package_item_raw`.
 * Has foreign keys to the tables:
 *
 * - `store`
 */
class m190320_124209_create_package_item_raw_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('package_item_raw', [
            'id' => $this->primaryKey()->comment('ID'),
            'store_id' => $this->integer(11)->notNull()->comment('Store ID reference'),
            'package_id' => $this->integer(11)->defaultValue(null)->comment("Package id after sent"),
            'package_item_id' => $this->integer(11)->null()->defaultValue(null)->comment("Package item id after sent"),
            'weshop_tag' => $this->string(60)->null()->defaultValue(null)->comment("Package item id after sent"),
            'order_id' => $this->integer(11)->null()->defaultValue(null)->comment('Order id'),
            'seller_id' => $this->integer(11)->null()->comment('Seller'),
            'seller_tracking' => $this->string(60)->null()->comment('Seller tracking'),
            'seller_weight' => $this->decimal(2)->defaultValue(0.00)->comment('seller Weight (kg)'),
            'seller_quantity' => $this->decimal(2)->defaultValue(0.00)->comment('seller quantity'),
            'seller_dimension_width' => $this->decimal(2)->defaultValue(0.00)->comment('Seller Width (cm)'),
            'seller_dimension_length' => $this->decimal(2)->defaultValue(0.00)->comment('Seller Length (cm)'),
            'seller_dimension_height' => $this->decimal(2)->defaultValue(0.00)->comment('Seller Height (cm)'),
            'seller_shipped_at' => $this->integer(11)->comment('Seller Shipped Time'),
            'receiver_warehouse_id' => $this->integer(11)->null()->comment('receiver warehouse (Kho hop nhat, kho anh lam, kho boxme tai my)'),
            'receiver_warehouse_note' => $this->text()->null()->comment('receiver warehouse note'),
            'receiver_warehouse_send_at' => $this->integer(11)->comment('Time when receiver warehouse send '),
            'local_warehouse_id' => $this->string(60)->null()->comment('local warehouse id (Boxme Ha Noi/Boxme HCM)'),
            'local_warehouse_tag' => $this->string(60)->null()->comment('local warehouse tag'),
            'local_warehouse_weight' => $this->string(60)->null()->comment('local warehouse weight'),
            'local_warehouse_quantity' => $this->string(60)->null()->comment('local warehouse quantity'),
            'local_warehouse_dimension_width' => $this->decimal(2)->defaultValue(0.00)->comment('local warehouse Width (cm)'),
            'local_warehouse_dimension_length' => $this->decimal(2)->defaultValue(0.00)->comment('local warehouse Length (cm)'),
            'local_warehouse_dimension_height' => $this->decimal(2)->defaultValue(0.00)->comment('local warehouse Height (cm)'),
            'local_warehouse_note' => $this->decimal(2)->defaultValue(0.00)->comment('local warehouse note'),
            'local_warehouse_status' => $this->string(10)->null()->comment('warehouse status (open/close)'),
            'local_warehouse_send_at' => $this->integer(11)->comment('Time when send to local warehouse'),
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

        $this->dropTable('package_item_raw');
    }
}
