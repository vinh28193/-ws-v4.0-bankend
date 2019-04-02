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
        try {
            $this->dropTable('draft_extension_tracking_map');

            echo "drop table success\n";
        } catch (Exception $exception) {
            echo 'Not have table';
        }
        $this->createTable('draft_extension_tracking_map', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'order_id' => $this->integer()->notNull(),
            'purchase_invoice_number' => $this->string()->notNull(),
            'status' => $this->string()->comment("trạng thái của tracking bên us"),
            'quatity' => $this->integer(),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'number_run' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        try {
            $this->dropTable('draft_data_tracking');

            echo 'Drop table success\n';
        } catch (Exception $exception) {
            echo 'Not have table';
        }
        $this->createTable('draft_data_tracking', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'quatity' => $this->integer(),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'purchase_invoice_number' => $this->string(),
            'number_get_detail' => $this->integer()->comment('Số lần chạy api lấy detail'),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        try {
            $this->dropTable('draft_boxme_tracking');

            echo 'Drop table success\n';
        } catch (Exception $exception) {
            echo 'Not have table';
        }
        $this->createTable('draft_boxme_tracking', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'quatity' => $this->integer(),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'purchase_invoice_number' => $this->string(),
            'number_callback' => $this->integer()->comment('Số lần callback'),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        try {
            $this->dropTable('draft_missing_tracking');

            echo 'Drop table success\n';
        } catch (Exception $exception) {
            echo 'Not have table';
        }
        $this->createTable('draft_missing_tracking', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'quatity' => $this->integer(),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        try {
            $this->dropTable('draft_wasting_tracking');

            echo 'Drop table success\n';
        } catch (Exception $exception) {
            echo 'Not have table';
        }
        $this->createTable('draft_wasting_tracking', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'quatity' => $this->integer(),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        try {
            $this->dropTable('draft_package_item');

            echo "Drop table success\n";
        } catch (Exception $exception) {
            echo 'Not have table';
        }
        $this->createTable('draft_package_item', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'quatity' => $this->integer(),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
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
