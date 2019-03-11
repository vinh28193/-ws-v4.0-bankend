<?php

use yii\db\Migration;

/**
 * Class m190219_094654_product
 */
class m190219_094654_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        /**** ToDo :
         * Cần Gộp category_id = custom_category_id : để tính phụ thu theo từng loại sản phẩm
         * . Ví dụ đồng hồ 6$, đồng hồ giá trị cao 10%, máy tính cũ 25$, mới 35$,
         ****/

        $this->createTable('product',[
            'id' => $this->primaryKey()->comment(''),
            'order_id' => $this->integer(11)->notNull()->comment('order id'),
            'seller_id' => $this->integer(11)->notNull()->comment(''),
            'portal' => $this->string(255)->notNull()->comment('portal sản phẩm, ebay, amazon us, amazon jp , etc....'),
            'sku' => $this->string(255)->notNull()->comment('sku của sản phẩm'),
            'parent_sku' => $this->string(255)->notNull()->comment('sku cha'),
            'link_img' => $this->text()->notNull()->comment('link ảnh sản phẩm'),
            'link_origin' => $this->text()->notNull()->comment('link gốc sản phẩm'),
            'category_id' => $this->integer(11)->comment('id danh mục trên Website Weshop bắt qua API'),
            'custom_category_id' => $this->integer(11)->comment('id danh mục phụ thu Hải Quản nếu api ko bắt được dang mục mà do sale chọn trong OPS thì sẽ thu thêm COD'),
            'price_amount' => $this->decimal(18,2)->notNull()->comment('đơn giá ngoại tệ'),
            'price_amount_local' => $this->decimal(18,2)->notNull()->comment('đơn giá local'),
            'total_price_amount_local' => $this->decimal(18,2)->notNull()->comment('tổng tiền hàng của từng sản phẩm'),
            'quantity' => $this->integer(11)->notNull()->comment('số lượng khách đặt'),
            'quantity_purchase' => $this->integer(11)->comment('số lượng Nhân viên đã mua'),
            'quantity_inspect' => $this->integer(11)->comment('số lượng đã kiểm'),
            'variations' => $this->text()->comment('thuộc tính sản phẩm'),
            'variation_id' => $this->integer(11)->comment('mã thuộc tính sản phẩm . Notes : Trường này để làm addon tự động mua hàng đẩy vào Giở hàng của Ebay / Amazon '),
            'note_by_customer' => $this->text()->comment('note của khách'),
            'total_weight_temporary' => $this->text()->notNull()->comment("cân nặng tạm tính"),
            'created_time' => $this->bigInteger()->notNull()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment("mặc định 0 là chưa xóa 1 là ẩn "),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190219_094654_product cannot be reverted.\n";
//        foreach ($this->list as $data){
//            $this->dropIndex('idx-product-'.$data['column'], 'product');
//            $this->dropForeignKey('fk-product-'.$data['column'], 'product');
//        }
//        $this->dropTable('product');
//        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190219_094654_product cannot be reverted.\n";

        return false;
    }
    */
}
