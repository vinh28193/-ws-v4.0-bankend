<?php

use yii\db\Migration;

/**
 * Class m190402_025429_add_new_table_for_tracking_and_package
 */
class m190402_025429_add_new_table_for_tracking_and_package extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('draft_extension_tracking_map',[
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'order_id' => $this->integer()->notNull(),
            'purchase_invoice_number' => $this->string()->notNull(),
            'status' => $this->string()->comment("trạng thái của tracking bên us"),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        $this->createTable('draft_data_tracking',[
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        $this->createTable('draft_boxme_tracking',[
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        $this->createTable('draft_missing_tracking',[
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        $this->createTable('draft_wasting_tracking',[
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        $this->createTable('draft_package_item',[
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190402_025429_add_new_table_for_tracking_and_package cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190402_025429_add_new_table_for_tracking_and_package cannot be reverted.\n";

        return false;
    }
    */
}
