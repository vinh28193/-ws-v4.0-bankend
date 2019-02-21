<?php

use yii\db\Migration;

/**
 * Class m190219_094654_product
 */
class m190219_094654_product extends Migration
{
    public $list = [
        [
            'column' => 'order_id',
            'table' => 'order',
        ],
        [
            'column' => 'category_id',
            'table' => 'category',
        ],
        [
            'column' => 'custom_category_id',
            'table' => 'category_custom_policy',
        ]
    ];
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product',[
            'id' => $this->primaryKey()->comment(''),
            'order_id' => $this->integer(11)->comment('order id'),
            'portal' => $this->string(255)->comment('portal sản phẩm, ebay, amazon us, amazon jp ....'),
            'sku' => $this->string(255)->comment('sku của sản phẩm'),
            'parent_sku' => $this->string(255)->comment('sku cha'),
            'link_img' => $this->text()->comment('link ảnh sản phẩm'),
            'link_origin' => $this->text()->comment('link gốc sản phẩm'),
            'category_id' => $this->integer(11)->comment('id danh mục'),
            'custom_category_id' => $this->integer(11)->comment('id danh mục phụ thu'),
            'price_amount' => $this->decimal(18,2)->comment('đơn giá ngoại tệ'),
            'price_amount_local' => $this->decimal(18,2)->comment('đơn giá local'),
            'total_price_amount_local' => $this->decimal(18,2)->comment('tổng tiền hàng của sản phẩm'),
            'quantity' => $this->integer(11)->comment('số lượng khách đặt'),
            'quantity_purchase' => $this->integer(11)->comment('số lượng đã mua'),
            'quantity_inspect' => $this->integer(11)->comment('số lượng đã kiểm'),
            'variations' => $this->text()->comment('thuộc tính sản phẩm'),
            'variation_id' => $this->integer(11)->comment('mã thuộc tính sản phẩm'),
            'note_by_customer' => $this->text()->comment('note của khách'),
            'total_weight_temporary' => $this->text()->comment("cân nặng tạm tính"),
            'created_time' => $this->bigInteger()->comment(""),
            'updated_time' => $this->bigInteger()->comment(""),
            'remove' => $this->tinyInteger(4)->comment(""),
        ]);

        foreach ($this->list as $data){
            $this->createIndex('idx-product-'.$data['column'],'product',$data['column']);
            $this->addForeignKey('fk-product-'.$data['column'], 'product', $data['column'], $data['table'], 'id');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190219_094654_product cannot be reverted.\n";
        foreach ($this->list as $data){
            $this->dropIndex('idx-product-'.$data['column'], 'product');
            $this->dropForeignKey('fk-product-'.$data['column'], 'product');
        }
        $this->dropTable('product');
        return false;
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
