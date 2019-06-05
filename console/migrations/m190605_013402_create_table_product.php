<?php

use yii\db\Migration;

class m190605_013402_create_table_product extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull()->comment('order id'),
            'seller_id' => $this->integer(11)->notNull(),
            'portal' => $this->string(255)->notNull()->comment('portal sản phẩm, ebay, amazon us, amazon jp , etc....'),
            'sku' => $this->string(255)->notNull()->comment('sku của sản phẩm'),
            'parent_sku' => $this->string(255)->notNull()->comment('sku cha'),
            'link_img' => $this->text()->notNull()->comment('link ảnh sản phẩm'),
            'link_origin' => $this->text()->notNull()->comment('link gốc sản phẩm'),
            'category_id' => $this->integer(11)->comment('id danh mục trên Website Weshop bắt qua API'),
            'custom_category_id' => $this->integer(11)->comment('id danh mục phụ thu Hải Quản nếu api ko bắt được dang mục mà do sale chọn trong OPS thì sẽ thu thêm COD'),
            'price_amount_origin' => $this->decimal(18, 2)->notNull()->comment('đơn giá gốc ngoại tệ'),
            'price_amount_local' => $this->decimal(18, 2)->notNull()->comment('đơn giá local'),
            'total_price_amount_local' => $this->decimal(18, 2)->notNull()->comment('tổng tiền hàng của từng sản phẩm'),
            'total_fee_product_local' => $this->decimal(18, 2)->comment('tổng phí trên sản phẩm'),
            'quantity_customer' => $this->integer(11)->notNull()->comment('số lượng khách đặt'),
            'quantity_purchase' => $this->integer(11)->comment('số lượng Nhân viên đã mua'),
            'quantity_inspect' => $this->integer(11)->comment('số lượng đã kiểm'),
            'price_purchase' => $this->decimal(18, 2)->comment('Giá khi nhân viên mua hàng'),
            'shipping_fee_purchase' => $this->decimal(18, 2)->comment('Phí ship khi nhân viên mua hàng'),
            'tax_fee_purchase' => $this->decimal(18, 2)->comment('Phí tax khi nhân viên mua hàng'),
            'variations' => $this->text()->comment('thuộc tính sản phẩm'),
            'variation_id' => $this->integer(11)->comment('mã thuộc tính sản phẩm . Notes : Trường này để làm addon tự động mua hàng đẩy vào Giở hàng của Ebay / Amazon '),
            'note_by_customer' => $this->text()->comment('note của khách / Khách hàng ghi chú'),
            'total_weight_temporary' => $this->decimal(18, 2),
            'created_at' => $this->bigInteger(20)->notNull(),
            'updated_at' => $this->bigInteger(20),
            'remove' => $this->tinyInteger(4)->comment('mặc định 0 là chưa xóa 1 là ẩn '),
            'product_name' => $this->text()->notNull(),
            'product_link' => $this->string(500),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'condition' => $this->string(255)->comment('Tình trạng đơn hàng'),
            'seller_refund_amount' => $this->decimal(18, 2)->comment('Số tiền người bán hoàn chả'),
            'note_boxme' => $this->string(255),
            'current_status' => $this->string(255)->defaultValue('NEW'),
            'purchase_start' => $this->integer(11),
            'purchased' => $this->integer(11),
            'seller_shipped' => $this->integer(11),
            'stockin_us' => $this->integer(11),
            'stockout_us' => $this->integer(11),
            'stockin_local' => $this->integer(11),
            'stockout_local' => $this->integer(11),
            'at_customer' => $this->integer(11),
            'returned' => $this->integer(11),
            'cancel' => $this->integer(11),
            'lost' => $this->integer(11),
            'refunded' => $this->integer(11),
            'confirm_change_price' => $this->integer(11)->comment('0: là không có thay đổi giá hoặc có thay đổi nhưng đã confirm. 1: là có thay đổi cần xác nhận'),
        ], $tableOptions);


        /*
        $this->createIndex('idx-product-category_id', '{{%product}}', 'category_id');
        $this->createIndex('idx-product-order_id', '{{%product}}', 'order_id');
        $this->createIndex('idx-product-custom_category_id', '{{%product}}', 'custom_category_id');
        $this->createIndex('idx-product-seller_id', '{{%product}}', 'seller_id');

        $this->addForeignKey('fk-product-custom_category_id', '{{%product}}', 'custom_category_id', '{{%category_custom_policy}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-product-order_id', '{{%product}}', 'order_id', '{{%order}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-product-seller_id', '{{%product}}', 'seller_id', '{{%seller}}', 'id', 'CASCADE', 'CASCADE');
        */
    }

    public function down()
    {
        $this->dropTable('{{%product}}');
    }
}
