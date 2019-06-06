<?php

use yii\db\Migration;

class m190606_042310_create_table_WS_PRODUCT extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%PRODUCT}}', [
            'id' => $this->integer()->notNull(),
            'order_id' => $this->integer()->notNull()->comment('order id'),
            'seller_id' => $this->integer()->notNull(),
            'portal' => $this->string(255)->notNull()->comment('portal s?n ph?m, ebay, amazon us, amazon jp , etc....'),
            'sku' => $this->string(255)->notNull()->comment('sku c?a s?n ph?m'),
            'parent_sku' => $this->string(255)->notNull()->comment('sku cha'),
            'link_img' => $this->text()->notNull()->comment('link ?nh s?n ph?m'),
            'link_origin' => $this->text()->notNull()->comment('link g?c s?n ph?m'),
            'category_id' => $this->integer()->comment('id danh m?c tren Website Weshop b?t qua API'),
            'custom_category_id' => $this->integer()->comment('id danh m?c ph? thu H?i Qu?n n?u api ko b?t d??c dang m?c ma do sale ch?n trong OPS thi s? thu them COD'),
            'price_amount_origin' => $this->decimal()->notNull()->comment('d?n gia g?c ngo?i t?'),
            'price_amount_local' => $this->decimal()->notNull()->comment('d?n gia local'),
            'total_price_amount_local' => $this->decimal()->notNull()->comment('t?ng ti?n hang c?a t?ng s?n ph?m'),
            'total_fee_product_local' => $this->decimal()->comment('t?ng phi tren s?n ph?m'),
            'quantity_customer' => $this->integer()->notNull()->comment('s? l??ng khach d?t'),
            'quantity_purchase' => $this->integer()->comment('s? l??ng Nhan vien d? mua'),
            'quantity_inspect' => $this->integer()->comment('s? l??ng d? ki?m'),
            'price_purchase' => $this->decimal()->comment('Gia khi nhan vien mua hang'),
            'shipping_fee_purchase' => $this->decimal()->comment('Phi ship khi nhan vien mua hang'),
            'tax_fee_purchase' => $this->decimal()->comment('Phi tax khi nhan vien mua hang'),
            'variations' => $this->text()->comment('thu?c tinh s?n ph?m'),
            'variation_id' => $this->integer()->comment('m? thu?c tinh s?n ph?m . Notes : Tr??ng nay d? lam addon t? d?ng mua hang d?y vao Gi? hang c?a Ebay / Amazon '),
            'note_by_customer' => $this->text()->comment('note c?a khach / Khach hang ghi chu'),
            'total_weight_temporary' => $this->decimal(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
            'remove' => $this->integer()->comment('m?c d?nh 0 la ch?a xoa 1 la ?n '),
            'product_name' => $this->text()->notNull(),
            'product_link' => $this->string(500),
            'version' => $this->string(255)->defaultValue('4.0')->comment('version 4.0'),
            'condition' => $this->string(255)->comment('Tinh tr?ng d?n hang'),
            'seller_refund_amount' => $this->decimal()->comment('S? ti?n ng??i ban hoan ch?'),
            'note_boxme' => $this->string(255),
            'current_status' => $this->string(255)->defaultValue('NEW'),
            'purchase_start' => $this->integer(),
            'purchased' => $this->integer(),
            'seller_shipped' => $this->integer(),
            'stockin_us' => $this->integer(),
            'stockout_us' => $this->integer(),
            'stockin_local' => $this->integer(),
            'stockout_local' => $this->integer(),
            'at_customer' => $this->integer(),
            'returned' => $this->integer(),
            'cancel' => $this->integer(),
            'lost' => $this->integer(),
            'refunded' => $this->integer(),
            'confirm_change_price' => $this->integer()->comment('0: la khong co thay d?i gia ho?c co thay d?i nh?ng d? confirm. 1: la co thay d?i c?n xac nh?n'),
        ], $tableOptions);

        $this->createIndex('SYS_IL0000108764C00028$$', '{{%PRODUCT}}', '', true);
        $this->createIndex('SYS_IL0000108764C00021$$', '{{%PRODUCT}}', '', true);
        $this->createIndex('SYS_IL0000108764C00023$$', '{{%PRODUCT}}', '', true);
        $this->createIndex('SYS_IL0000108764C00007$$', '{{%PRODUCT}}', '', true);
        $this->createIndex('SYS_IL0000108764C00008$$', '{{%PRODUCT}}', '', true);
    }

    public function down()
    {
        $this->dropTable('{{%PRODUCT}}');
    }
}
