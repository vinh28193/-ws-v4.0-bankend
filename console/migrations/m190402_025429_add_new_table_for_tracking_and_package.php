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
        $this->execute("DROP TABLE IF EXISTS `draft_extension_tracking_map`");
        $this->createTable('draft_extension_tracking_map', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'order_id' => $this->integer()->notNull(),
            'purchase_invoice_number' => $this->string()->notNull(),
            'status' => $this->string()->comment("trạng thái của tracking bên us"),
            'quantity' => $this->integer(),
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
        $this->addCommentOnTable('draft_extension_tracking_map','Bảng dùng để lưu những tracking từ 1 extension lưu về');

        $this->execute("DROP TABLE IF EXISTS `draft_data_tracking`");
        $this->createTable('draft_data_tracking', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'quantity' => $this->integer(),
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
        $this->addCommentOnTable('draft_data_tracking','Bảng dùng để tổ hợp dữ liệu của 2 bàng draft_extension_tracking_map và tracking_code (US sending)');

        $this->execute("DROP TABLE IF EXISTS `draft_boxme_tracking`");
        $this->createTable('draft_boxme_tracking', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'quantity' => $this->integer(),
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
        $this->addCommentOnTable('draft_data_tracking','Bảng dùng để đối chiếu với callback kiểm hàng, được tạo ra khi bảng draft_data_tracking đối chiếu với api get detail của box me');
        $this->execute("DROP TABLE IF EXISTS `draft_missing_tracking`");
        $this->createTable('draft_missing_tracking', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'quantity' => $this->integer(),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'item_name' => $this->string()->comment("tên sản phẩm trả về từ boxme"),
            'warehouse_tag_boxme' => $this->text()->comment("wtag của boxme"),
            'note_boxme' => $this->text()->comment("note của boxme"),
            'image' => $this->text()->comment("các hình ảnh cách nhau bởi dấu phẩy"),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        $this->execute("DROP TABLE IF EXISTS `draft_wasting_tracking`");
        $this->createTable('draft_wasting_tracking', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'quantity' => $this->integer(),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'item_name' => $this->string()->comment("tên sản phẩm trả về từ boxme"),
            'warehouse_tag_boxme' => $this->text()->comment("wtag của boxme"),
            'note_boxme' => $this->text()->comment("note của boxme"),
            'image' => $this->text()->comment("các hình ảnh cách nhau bởi dấu phẩy"),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        $this->execute("DROP TABLE IF EXISTS `draft_package_item`");
        $this->createTable('draft_package_item', [
            'id' => $this->primaryKey(),
            'tracking_code' => $this->string()->notNull(),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'quantity' => $this->integer(),
            'weight' => $this->float(),
            'dimension_l' => $this->float(),
            'dimension_w' => $this->float(),
            'dimension_h' => $this->float(),
            'manifest_id' => $this->integer(),
            'manifest_code' => $this->string(),
            'item_name' => $this->string()->comment("tên sản phẩm trả về từ boxme"),
            'warehouse_tag_boxme' => $this->text()->comment("wtag của boxme"),
            'note_boxme' => $this->text()->comment("note của boxme"),
            'image' => $this->text()->comment("các hình ảnh cách nhau bởi dấu phẩy"),
            'purchase_invoice_number' => $this->string(),
            'status' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
        $this->addCommentOnTable('draft_package_item','Bảng packing tạm');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('draft_extension_tracking_map');
        $this->dropTable('draft_data_tracking');
        $this->dropTable('draft_boxme_tracking');
        $this->dropTable('draft_missing_tracking');
        $this->dropTable('draft_wasting_tracking');
        $this->dropTable('draft_package_item');

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
