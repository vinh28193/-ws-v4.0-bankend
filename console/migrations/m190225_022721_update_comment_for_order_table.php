<?php

use yii\db\Migration;

/**
 * Class m190225_022721_update_comment_for_order_table
 */
class m190225_022721_update_comment_for_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addCommentOnColumn('order','total_origin_fee_local','Tổng phí gốc tại xuất xứ (Tiền Local)');
        $this->addCommentOnColumn('order','total_origin_tax_fee_local','Tổng phí tax tại xuất xứ');
        $this->addCommentOnColumn('order','total_origin_shipping_fee_local','Tổng phí vận chuyển tại xuất xứ');
        $this->addCommentOnColumn('order','total_weshop_fee_local','Tổng phí Weshop');
        $this->addCommentOnColumn('order','total_intl_shipping_fee_local','Tổng phí vận chuyển quốc tế');
        $this->addCommentOnColumn('order','total_delivery_fee_local','Tổng phí vận chuyển nội địa');
        $this->addCommentOnColumn('order','total_packing_fee_local','Tống phí hàng');
        $this->addCommentOnColumn('order','total_inspection_fee_local','Tổng phí kiểm hàng');
        $this->addCommentOnColumn('order','total_insurance_fee_local','Tổng phí bảo hiểm');
        $this->addCommentOnColumn('order','total_vat_amount_local','Tổng phí VAT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190225_022721_update_comment_for_order_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190225_022721_update_comment_for_order_table cannot be reverted.\n";

        return false;
    }
    */
}
